# Chipper Together

Chipper Together is a collaborative team forum designed to help members of a company or organization communicate, share ideas, and stay connected. The platform allows team members to post short messages called Chips, respond to colleagues’ posts, and engage in threaded discussions — fostering transparency, collaboration, and quick knowledge sharing.

Repository: [fortune-c/chipper](https://github.com/fortune-c/chipper)

## About the Project

Chipper Together aims to bridge the gap between formal communication tools and casual team interaction. By providing a secure, company-focused social platform, teams can maintain transparency while fostering innovation through quick idea sharing and collaborative discussions.

### Vision
To create the ultimate internal communication platform that combines the best aspects of social media engagement with enterprise-grade security and productivity features.

## Key Features

### Collaborative Communication
- **Rich Chips (Posts)**: Share messages with multimedia attachments (images, videos, docs).
- **Threaded Discussions**: Nested replies to keep conversations organized.
- **Live Reactions**: Express feedback instantly with emoji reactions on posts and messages.
- **Mentions & Notifications**: Tag team members (`@username`) to loop them in, with real-time alerts.
- **Polls**: Gather team sentiment quickly with integrated voting.

### Real-time Messaging
- **Direct & Group Chats**: Private or team-based conversations.
- **Media Sharing**: Drag-and-drop file sharing in chat.
- **Video/Audio Calls**: Integrated calling interface for quick syncs.
- **Read Status**: Know when your messages have been seen.

### Productivity Tools
- **Task Management**: Personal to-do lists integrated directly into the dashboard.
- **Meeting Scheduler**: Coordinate and view upcoming team meetings.
- **Status Updates**: Let your team know your availability with custom status messages and emojis.

## Tech Stack
- **Backend**: Laravel 12 (PHP 8.2+)
- **Database**: PostgreSQL (Production on Render) / SQLite (Local/Dev)
- **Frontend**: Blade Templates + Tailwind CSS + Alpine.js
- **Build Tooling**: Vite (Node.js/npm)
- **Containerization**: Docker (optimized for Render deployment)

## Prerequisites
- PHP 8.2+
- Composer
- Node.js & npm (for compiling assets)
- PostgreSQL or SQLite
- Git

## Deployment & Setup

### Docker Deployment (Render)
This project is configured for deployment on Render using Docker.
1.  The `Dockerfile` handles dependency installation (PHP, Composer, Node.js).
2.  The `docker-entrypoint.sh` script runs on startup to:
    -   Clear and rebuild caches (`php artisan optimize`).
    -   Run database migrations automatically (`php artisan migrate --force`).
    -   Start the Apache server.

**Important**: Ensure your environment variables (especially `DB_CONNECTION`, `DB_HOST`, etc.) are correctly set in your deployment environment.

### Local Development

1.  **Clone the repo**
    ```bash
    git clone https://github.com/fortune-c/chipper.git
    cd chipper
    ```

2.  **Install Dependencies**
    ```bash
    composer install
    npm install
    ```

3.  **Environment Setup**
    ```bash
    cp .env.example .env
    # Edit .env to set DB_CONNECTION=sqlite or pgsql
    ```

4.  **Key & Migrations**
    ```bash
    php artisan key:generate
    touch database/database.sqlite # If using SQLite
    php artisan migrate
    php artisan storage:link
    ```

5.  **Serve**
    ```bash
    npm run dev
    php artisan serve
    ```

## Contributing
Contributions are welcome. Please fork the repository, make your changes on a feature branch, and open a pull request with a clear description of your changes.

## License
MIT — see LICENSE file for details.

## Contact
Project maintained by fortune-c — open issues or pull requests in the repository: https://github.com/fortune-c/chipper
