import * as THREE from 'three';
import { gsap } from 'gsap';

export function initDashboardScene(sceneManager) {
    if (!sceneManager || !sceneManager.scene) return;

    const scene = sceneManager.scene;
    const items = [];

    // Create a group for dashboard elements
    const dashboardGroup = new THREE.Group();
    scene.add(dashboardGroup);

    // Knowledge Galaxy / Constellation Effect
    const particleCount = 150;
    const geometry = new THREE.BufferGeometry();
    const positions = new Float32Array(particleCount * 3);
    const colors = new Float32Array(particleCount * 3);
    const sizes = new Float32Array(particleCount);

    const colorPalette = [
        new THREE.Color(0x4f46e5), // Indigo
        new THREE.Color(0x14b8a6), // Teal
        new THREE.Color(0xf59e0b), // Amber
        new THREE.Color(0xec4899)  // Pink
    ];

    for (let i = 0; i < particleCount; i++) {
        const x = (Math.random() - 0.5) * 25;
        const y = (Math.random() - 0.5) * 15;
        const z = (Math.random() - 0.5) * 10 - 5;

        positions[i * 3] = x;
        positions[i * 3 + 1] = y;
        positions[i * 3 + 2] = z;

        const color = colorPalette[Math.floor(Math.random() * colorPalette.length)];
        colors[i * 3] = color.r;
        colors[i * 3 + 1] = color.g;
        colors[i * 3 + 2] = color.b;

        sizes[i] = Math.random() * 0.2;
    }

    geometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));
    geometry.setAttribute('color', new THREE.BufferAttribute(colors, 3));
    geometry.setAttribute('size', new THREE.BufferAttribute(sizes, 1));

    const material = new THREE.PointsMaterial({
        size: 0.15,
        vertexColors: true,
        transparent: true,
        opacity: 0.8,
        blending: THREE.AdditiveBlending
    });

    const particles = new THREE.Points(geometry, material);
    dashboardGroup.add(particles);

    // Floating Abstract Shapes (representing "Modules" or "Units")
    const shapeGeometries = [
        new THREE.IcosahedronGeometry(0.5, 0),
        new THREE.OctahedronGeometry(0.4, 0),
        new THREE.TetrahedronGeometry(0.4, 0)
    ];

    for (let i = 0; i < 5; i++) {
        const geo = shapeGeometries[Math.floor(Math.random() * shapeGeometries.length)];
        const mat = new THREE.MeshPhongMaterial({
            color: colorPalette[Math.floor(Math.random() * colorPalette.length)],
            wireframe: true,
            transparent: true,
            opacity: 0.3
        });

        const mesh = new THREE.Mesh(geo, mat);
        mesh.position.set(
            (Math.random() - 0.5) * 10,
            (Math.random() - 0.5) * 8,
            (Math.random() - 0.5) * 5
        );

        mesh.userData = {
            rotSpeed: (Math.random() - 0.5) * 0.02,
            floatSpeed: 0.002 + Math.random() * 0.002,
            originalY: mesh.position.y
        };

        dashboardGroup.add(mesh);
        items.push(mesh);
    }

    // Animation Loop
    const animate = () => {
        if (!document.getElementById('canvas-container')) return;

        requestAnimationFrame(animate);

        const time = Date.now() * 0.001;

        // Rotate particle system slowly
        particles.rotation.y = time * 0.05;
        particles.rotation.x = Math.sin(time * 0.02) * 0.05;

        // Animate floating shapes
        items.forEach(item => {
            item.rotation.x += item.userData.rotSpeed;
            item.rotation.y += item.userData.rotSpeed;
            item.position.y = item.userData.originalY + Math.sin(time + item.position.x) * 0.5;
        });
    };

    animate();

    return dashboardGroup;
}
