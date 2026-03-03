import * as THREE from 'three';
import { gsap } from 'gsap';

export class Book {
    constructor() {
        this.mesh = new THREE.Group();
        this.isOpen = false;

        this.init();
    }

    init() {
        // Book Dimensions
        const width = 2.5;
        const height = 3.5;
        const depth = 0.5;
        const coverThickness = 0.1;

        const coverMaterial = new THREE.MeshStandardMaterial({
            color: 0x0f5e9c, // Primary Blue
            roughness: 0.25,
            metalness: 0.15
        });

        const pageMaterial = new THREE.MeshStandardMaterial({
            color: 0xffffff,
            roughness: 0.8
        });

        // Current Book Structure: 
        // We need a spine, front cover, back cover, and pages block.
        // For animation (opening), the front cover needs to rotate around the spine.

        // Spine
        const spineGeo = new THREE.BoxGeometry(depth, height, coverThickness);
        const spine = new THREE.Mesh(spineGeo, coverMaterial);
        spine.position.x = -width / 2;
        this.mesh.add(spine);

        // Back Cover
        const backCoverGeo = new THREE.BoxGeometry(width, height, coverThickness);
        const backCover = new THREE.Mesh(backCoverGeo, coverMaterial);
        backCover.position.z = -depth / 2 + coverThickness / 2;
        this.mesh.add(backCover);

        // Front Cover Group (for rotation)
        this.frontCoverGroup = new THREE.Group();
        this.frontCoverGroup.position.x = -width / 2; // Pivot at spine
        this.frontCoverGroup.position.z = depth / 2 - coverThickness / 2;

        const frontCoverGeo = new THREE.BoxGeometry(width, height, coverThickness);
        const frontCover = new THREE.Mesh(frontCoverGeo, coverMaterial);
        frontCover.position.x = width / 2; // Offset center relative to pivot

        this.frontCoverGroup.add(frontCover);
        this.mesh.add(this.frontCoverGroup);

        // Pages (Block)
        const pagesGeo = new THREE.BoxGeometry(width - 0.2, height - 0.2, depth - 2 * coverThickness);
        const pages = new THREE.Mesh(pagesGeo, pageMaterial);
        pages.position.x = 0.1; // Slight indent from spine
        this.mesh.add(pages);

        // Initial Rotation for presentation
        this.mesh.rotation.y = -Math.PI / 6;
        this.mesh.rotation.x = Math.PI / 12;
    }

    animate(time) {
        // Gentle float
        this.mesh.position.y = Math.sin(time * 0.5) * 0.2;

        // Gentle rotation
        this.mesh.rotation.y = -Math.PI / 6 + Math.sin(time * 0.3) * 0.1;
    }

    hoverOpen() {
        if (this.isOpen) return;
        this.isOpen = true;

        gsap.to(this.frontCoverGroup.rotation, {
            y: -Math.PI / 3, // Open 60 degrees
            duration: 0.8,
            ease: "power2.out"
        });
    }

    hoverClose() {
        if (!this.isOpen) return;
        this.isOpen = false;

        gsap.to(this.frontCoverGroup.rotation, {
            y: 0,
            duration: 0.8,
            ease: "power2.out"
        });
    }
}
