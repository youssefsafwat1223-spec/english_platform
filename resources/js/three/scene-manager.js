import * as THREE from 'three';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

export class SceneManager {
    constructor() {
        this.container = document.getElementById('canvas-container');

        if (!this.container) {
            console.warn('Canvas container not found, skipping 3D initialization.');
            return;
        }

        this.scene = null;
        this.camera = null;
        this.renderer = null;
        this.objects = [];
        this.mouse = { x: 0, y: 0 };
        this.clock = new THREE.Clock();

        this.init();
        this.createGlobalBackground();
        this.setupMouseTracking();
        this.setupScrollAnimation();
    }

    init() {
        // Scene
        this.scene = new THREE.Scene();
        this.scene.background = null; // Transparent

        // Camera
        this.camera = new THREE.PerspectiveCamera(
            75, window.innerWidth / window.innerHeight, 0.1, 1000
        );
        this.camera.position.z = 8;

        // Renderer
        this.renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true });
        this.renderer.setSize(window.innerWidth, window.innerHeight);
        this.renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
        this.container.appendChild(this.renderer.domElement);

        // Lights
        const ambientLight = new THREE.AmbientLight(0xffffff, 0.6);
        this.scene.add(ambientLight);

        const directionalLight = new THREE.DirectionalLight(0xffffff, 0.8);
        directionalLight.position.set(5, 5, 5);
        this.scene.add(directionalLight);

        const pointLight1 = new THREE.PointLight(0x0f5e9c, 1.2, 50); // Primary
        pointLight1.position.set(-5, 3, 2);
        this.scene.add(pointLight1);

        const pointLight2 = new THREE.PointLight(0xf59e0b, 1.1, 50); // Accent
        pointLight2.position.set(5, -3, 2);
        this.scene.add(pointLight2);

        // Resize
        window.addEventListener('resize', () => this.onResize());

        this.setupThemeObserver();

        // Animate
        this.animate();
    }

    createGlobalBackground() {
        // === Floating Geometric Shapes ===
        const shapes = [];
        const geometries = [
            new THREE.IcosahedronGeometry(0.4, 0),
            new THREE.OctahedronGeometry(0.35, 0),
            new THREE.TetrahedronGeometry(0.3, 0),
            new THREE.DodecahedronGeometry(0.3, 0),
            new THREE.TorusGeometry(0.25, 0.1, 8, 16),
            new THREE.SphereGeometry(0.2, 12, 12),
        ];

        const colors = [
            0x0f5e9c, // Primary Blue
            0x2b7fdc, // Soft Blue
            0x10b981, // Emerald
            0xf59e0b, // Amber
            0xf97316, // Orange
            0x14b8a6, // Teal
            0x1f2937, // Slate
            0x64748b, // Muted
        ];

        for (let i = 0; i < 30; i++) {
            const geo = geometries[Math.floor(Math.random() * geometries.length)];
            const mat = new THREE.MeshPhongMaterial({
                color: colors[Math.floor(Math.random() * colors.length)],
                transparent: true,
                opacity: 0.15 + Math.random() * 0.25,
                wireframe: Math.random() > 0.5,
                shininess: 100,
            });

            const mesh = new THREE.Mesh(geo, mat);
            mesh.position.set(
                (Math.random() - 0.5) * 25,
                (Math.random() - 0.5) * 18,
                (Math.random() - 0.5) * 10 - 3
            );
            mesh.rotation.set(
                Math.random() * Math.PI * 2,
                Math.random() * Math.PI * 2,
                Math.random() * Math.PI * 2
            );

            // Store animation data
            mesh.userData = {
                rotSpeed: {
                    x: (Math.random() - 0.5) * 0.02,
                    y: (Math.random() - 0.5) * 0.02,
                    z: (Math.random() - 0.5) * 0.01,
                },
                floatSpeed: 0.3 + Math.random() * 0.7,
                floatAmplitude: 0.3 + Math.random() * 0.5,
                originalY: mesh.position.y,
                originalX: mesh.position.x,
                phase: Math.random() * Math.PI * 2,
            };

            this.scene.add(mesh);
            shapes.push(mesh);
        }
        this.objects = shapes;

        // === Particle System (Stars / Dust) ===
        const particleCount = 200;
        const particleGeometry = new THREE.BufferGeometry();
        const positions = new Float32Array(particleCount * 3);
        const sizes = new Float32Array(particleCount);

        for (let i = 0; i < particleCount; i++) {
            positions[i * 3] = (Math.random() - 0.5) * 30;
            positions[i * 3 + 1] = (Math.random() - 0.5) * 20;
            positions[i * 3 + 2] = (Math.random() - 0.5) * 15 - 5;
            sizes[i] = Math.random() * 3 + 1;
        }

        particleGeometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));
        particleGeometry.setAttribute('size', new THREE.BufferAttribute(sizes, 1));

        const particleMaterial = new THREE.PointsMaterial({
            color: 0x0f5e9c,
            size: 0.05,
            transparent: true,
            opacity: 0.4,
            blending: THREE.AdditiveBlending,
        });

        this.particles = new THREE.Points(particleGeometry, particleMaterial);
        this.scene.add(this.particles);

        // === Connecting Lines (Constellations) ===
        const lineGeometry = new THREE.BufferGeometry();
        const linePositions = [];

        // Connect nearby shapes with faint lines
        for (let i = 0; i < shapes.length; i++) {
            for (let j = i + 1; j < shapes.length; j++) {
                const dist = shapes[i].position.distanceTo(shapes[j].position);
                if (dist < 6) {
                    linePositions.push(
                        shapes[i].position.x, shapes[i].position.y, shapes[i].position.z,
                        shapes[j].position.x, shapes[j].position.y, shapes[j].position.z
                    );
                }
            }
        }

        if (linePositions.length > 0) {
            lineGeometry.setAttribute('position', new THREE.Float32BufferAttribute(linePositions, 3));
            const lineMaterial = new THREE.LineBasicMaterial({
                color: 0x0f5e9c,
                transparent: true,
                opacity: 0.06,
            });
            this.connectionLines = new THREE.LineSegments(lineGeometry, lineMaterial);
            this.scene.add(this.connectionLines);
        }
    }

    setupMouseTracking() {
        document.addEventListener('mousemove', (e) => {
            this.mouse.x = (e.clientX / window.innerWidth) * 2 - 1;
            this.mouse.y = -(e.clientY / window.innerHeight) * 2 + 1;
        });
    }

    setupScrollAnimation() {
        // Rotate camera slightly based on scroll
        window.addEventListener('scroll', () => {
            const scrollY = window.scrollY;
            const maxScroll = document.body.scrollHeight - window.innerHeight;
            const scrollProgress = maxScroll > 0 ? scrollY / maxScroll : 0;

            // Slowly shift camera and scene on scroll
            gsap.to(this.camera.position, {
                y: -scrollProgress * 3,
                duration: 0.8,
                ease: 'power2.out',
            });

            gsap.to(this.scene.rotation, {
                y: scrollProgress * 0.3,
                duration: 0.8,
                ease: 'power2.out',
            });
        });
    }

    animate() {
        requestAnimationFrame(() => this.animate());

        const time = this.clock.getElapsedTime();

        // Animate shapes
        this.objects.forEach(obj => {
            const ud = obj.userData;
            obj.rotation.x += ud.rotSpeed.x;
            obj.rotation.y += ud.rotSpeed.y;
            obj.rotation.z += ud.rotSpeed.z;

            // Float up/down
            obj.position.y = ud.originalY + Math.sin(time * ud.floatSpeed + ud.phase) * ud.floatAmplitude;
            // Gentle horizontal sway
            obj.position.x = ud.originalX + Math.sin(time * ud.floatSpeed * 0.5 + ud.phase) * 0.3;
        });

        // Rotate particles slowly
        if (this.particles) {
            this.particles.rotation.y = time * 0.02;
            this.particles.rotation.x = Math.sin(time * 0.01) * 0.1;
        }

        // Subtle camera movement following mouse
        if (this.camera) {
            this.camera.position.x += (this.mouse.x * 0.5 - this.camera.position.x) * 0.02;
            // Keep y influenced by both mouse and scroll
            const mouseInfluence = this.mouse.y * 0.3;
            this.camera.position.y += (mouseInfluence - this.camera.position.y) * 0.01;
        }

        this.renderer.render(this.scene, this.camera);
    }



    setupThemeObserver() {
        this.updateTheme(); // Initial check

        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.attributeName === 'class') {
                    this.updateTheme();
                }
            });
        });

        observer.observe(document.documentElement, {
            attributes: true
        });
    }

    updateTheme() {
        const isDark = document.documentElement.classList.contains('dark');

        // Adjust Lights
        if (this.scene) {
            this.scene.children.forEach(child => {
                if (child.isAmbientLight) {
                    gsap.to(child, { intensity: isDark ? 0.2 : 0.6, duration: 1 });
                }
                if (child.isDirectionalLight) {
                    gsap.to(child, { intensity: isDark ? 0.4 : 0.8, duration: 1 });
                }
            });
        }

        // Adjust Background Shapes
        if (this.objects) {
            this.objects.forEach(obj => {
                if (obj.material) {
                    // Make shapes more subtle in dark mode, or more glowing
                    gsap.to(obj.material, {
                        opacity: isDark ? 0.1 : 0.15 + Math.random() * 0.25,
                        duration: 1
                    });
                }
            });
        }

        // Adjust Particles (Stars)
        if (this.particles && this.particles.material) {
            gsap.to(this.particles.material, {
                opacity: isDark ? 0.8 : 0.4, // Brighter stars in dark mode
                size: isDark ? 0.07 : 0.05,
                duration: 1
            });
        }

        // Adjust Connection Lines
        if (this.connectionLines && this.connectionLines.material) {
            gsap.to(this.connectionLines.material, {
                opacity: isDark ? 0.1 : 0.06,
                duration: 1
            });
        }
    }

    onResize() {
        this.camera.aspect = window.innerWidth / window.innerHeight;
        this.camera.updateProjectionMatrix();
        this.renderer.setSize(window.innerWidth, window.innerHeight);
    }

    dispose() {
        if (this.renderer) {
            this.renderer.dispose();
            this.container.removeChild(this.renderer.domElement);
        }
    }
}
