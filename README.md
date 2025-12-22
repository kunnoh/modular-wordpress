# modular-wordpress

## Overview
This project provides a production-ready WordPress platform built around immutable infrastructure principles.
WordPress plugins are treated as versioned, external dependencies, sourced from multiple Git repositories and assembled into a single Docker image during CI/CD.

No plugins are installed at runtime. Every deployment is reproducible, auditable, and promotion-safe.


### Key Goals
- Deterministic WordPress builds
- Plugin-as-code (Git-based plugin management)
- Secure, immutable Docker images
- CI/CD-driven releases
- Kubernetes-ready architecture

## Architecture
```
┌────────────┐  
│ Plugin Repos│  
│ (multiple) │  
└─────┬──────┘  
      │
      ▼
┌──────────────────────┐
│ WordPress Base Repo  │
│ plugins.manifest.yml │
└─────────┬────────────┘
          │
          ▼
┌──────────────────────┐
│ CI/CD Pipeline       │
│ Docker Image Build   │
│ Plugin Fetch & Pin   │
└─────────┬────────────┘
          │
          ▼
┌──────────────────────┐
│ Container Registry   │
└─────────┬────────────┘
          │
          ▼
┌──────────────────────┐
│ Runtime              │
│ (K8s / Compose)      │
└──────────────────────┘
```

## Repository Structure
```sh
.
├── Dockerfile
├── plugins.manifest.yaml
├── wp-config.php
├── docker/
│   ├── fetch-plugins.sh
│   └── entrypoint.sh
├── .github/
│   └── workflows/
│       └── build.yml
└── README.md
```

## Plugin Management Model
Plugins are defined declaratively in a single manifest file.

plugins.manifest.yaml
```yml
plugins:
  - name: seo
    repo: https://github.com/yourname/wp-plugin-seo.git
    ref: v1.2.0

  - name: payments
    repo: https://github.com/yourname/wp-plugin-payments.git
    ref: main
```

### Why this approach?
- Version pinning → reproducible builds.  
- Multiple repositories → team ownership per plugin.  
- No admin UI installs → supply-chain safety.  
- Easy rollbacks → rebuild image from previous commit.  

## Docker Image Build

The image is built using a multi-stage Dockerfile.

### Build behavior
- WordPress base image.  
- Plugins fetched during build.  
- Final image contains:  
    - WordPress core.  
    - All required plugins.  
    - No build tools or Git binaries.  

### Benefits
- Smaller runtime image.  
- No network access required at runtime.  
- Faster container startup.  
- Predictable deployments.  

## CI/CD Pipeline
### Trigger Conditions.  
- Push to main.  
- Manual trigger (workflow_dispatch).  

### Pipeline Stages
1. Checkout source.  
2. Build Docker image.  
3. Pull plugins from Git repos.  
4. Tag image using commit SHA.  
5. Push image to container registry.  
6. (Optional) Security scanning.  

### Example Image Tagging Strategy
```sh
ghcr.io/yourname/wp:commit-sha
ghcr.io/yourname/wp:latest
```

## Deployment
### Environment Support
- Development  
- Staging  
- Production

### Runtime Options
#### Docker Compose
```sh
docker compose up -d
```

#### Kubernetes
- Deployment  
- Service  
- Ingress  
- PersistentVolume (uploads only)  
- Secrets for DB credentials

**Note:**
The container filesystem is immutable. Only **/wp-content/uploads** should be persisted.  

## Configuration
### Environment Variables
|Variable	|Description  
WORDPRESS_DB_HOST	Database host  
WORDPRESS_DB_NAME	Database name  
WORDPRESS_DB_USER	Database user  
WORDPRESS_DB_PASSWORD	Database password  

### Security Considerations
- Plugins sourced only from trusted Git repositories
- No runtime plugin installation
- Optional checksum verification
- Image scanning supported (Trivy)  
- Secrets never baked into image  

## Why This Project Exists
Traditional WordPress deployments suffer from:
- Snowflake servers.  
- Manual plugin installs.  
- Unsafe updates.  
- Impossible rollbacks.  
This project applies modern DevOps and platform engineering practices to WordPress without sacrificing flexibility.  

## Maintainer
### Dereba (DevOps Engineer)
Focus areas:
- Linux systems
- CI/CD pipelines
- Kubernetes platforms
- Secure containerized workloads