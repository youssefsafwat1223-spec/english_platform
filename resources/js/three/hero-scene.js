import * as THREE from 'three';
import { gsap } from 'gsap';
import { Book } from './components/Book';

export function initHeroScene(sceneManager) {
    if (!sceneManager || !sceneManager.scene) return;

    const scene = sceneManager.scene;
    const items = [];

    // Create a group for the hero elements
    const heroGroup = new THREE.Group();
    // Position it to the right side
    heroGroup.position.set(2.5, -0.5, 0);
    // Rotate slightly towards camera
    heroGroup.rotation.y = -0.2;
    scene.add(heroGroup);

    // Main Object: The Book
    const book = new Book();
    heroGroup.add(book.mesh);
    items.push(book);

    // Satellites / Magic Particles
    const particles = new THREE.Group();
    heroGroup.add(particles);

    for (let i = 0; i < 50; i++) {
        const satGeo = new THREE.SphereGeometry(0.05, 8, 8);
        const satMat = new THREE.MeshBasicMaterial({
            color: 0x0f5e9c,
            transparent: true,
            opacity: 0.6 + Math.random() * 0.4
        });
        const sat = new THREE.Mesh(satGeo, satMat);

        // Random position around the book
        const angle = Math.random() * Math.PI * 2;
        const radius = 1.5 + Math.random() * 2;
        const height = (Math.random() - 0.5) * 4;

        sat.position.set(
            Math.cos(angle) * radius,
            height,
            Math.sin(angle) * radius
        );

        sat.userData = {
            angle: angle,
            speed: 0.005 + Math.random() * 0.01,
            radius: radius,
            ySpeed: (Math.random() - 0.5) * 0.01,
            originalY: height
        };

        particles.add(sat);
    }
    items.push(particles);

    // Ambient Light specific to hero if needed, but scene has global lights.
    // Let's add a spotlight highlighting the book
    const spotLight = new THREE.SpotLight(0xffffff, 1.8);
    spotLight.position.set(5, 5, 5);
    spotLight.angle = Math.PI / 6;
    spotLight.penumbra = 1;
    heroGroup.add(spotLight);

    // Animation Loop
    const animate = () => {
        // If scene is disposed, stop.
        if (!document.getElementById('canvas-container')) return;

        requestAnimationFrame(animate);

        const time = Date.now() * 0.001;

        // Animate Book
        if (book && book.animate) {
            book.animate(time);
        }

        // Animate Particles
        particles.children.forEach(sat => {
            sat.userData.angle += sat.userData.speed;
            sat.position.x = Math.cos(sat.userData.angle) * sat.userData.radius;
            sat.position.z = Math.sin(sat.userData.angle) * sat.userData.radius;
            sat.position.y = sat.userData.originalY + Math.sin(time + sat.userData.angle) * 0.2;
        });

        // Float the whole group slightly
        heroGroup.position.y = -0.5 + Math.sin(time * 0.5) * 0.1;
    };

    animate();

    return heroGroup;
}
