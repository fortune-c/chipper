# Chipper Together

Chipper Together is a collaborative team forum designed to help members of a company or organization communicate, share ideas, and stay connected. The platform allows team members to post short messages called Chips, respond to colleagues’ posts, and engage in threaded discussions — fostering transparency, collaboration, and quick knowledge sharing.

Repository: [fortune-c/chipper](https://github.com/fortune-c/chipper)

## About the Project

Chipper Together aims to bridge the gap between formal communication tools and casual team interaction. By providing a secure, company-focused social platform, teams can maintain transparency while fostering innovation through quick idea sharing and collaborative discussions.

### Vision
To create the ultimate internal communication platform that combines the best aspects of social media engagement with enterprise-grade security and productivity features.

## Key Features (Chipper Together)

### Collaborative Communication
- **Rich Chips (Posts)**: Share messages with multimedia attachments (images, videos, docs).
- **Threaded Discussions**: Nested replies to keep conversations organized.
- **Live Reactions**: Express feedback instantly with emoji reactions on posts and messages.
- **Mentions & Notifications**: Tag team members (@user) to loop them in, with real-time alerts.
- **Polls**: Gather team sentiment quickly with integrated voting.

### Real-time Messaging
- **Direct & Group Chats**: Private or team-based conversations.
- **Media Sharing**: Drag-and-drop file sharing in chat.
- **Video/Audio Calls**: Integrated calling interface for quick syncs.
- **Read Status**: Know when your messages have been seen.

### Productivity Tools
- **Task Management**: Personal to-do lists integrated directly into the dashboard.
- **Meeting Scheduler**: Coordinate and view upcoming team meetings.
- **Status Updates**: Let your team know your availability with custom status messages.

## Tech stack
- Backend: Laravel (PHP)
- Database: Sqlite (configurable via .env)
- Frontend tooling: Node / npm (for asset compilation)
- Optional: Laravel Queues, Scheduler, and Storage (for attachments)

## Prerequisites
- PHP 8.1+ (or the version your Laravel release requires)
- Composer
- Node.js & npm (for compiling assets)
- A database server (Sqlite )
- Git

## Quick start

1. Clone the repo
   git clone https://github.com/fortune-c/chipper.git
   cd chipper

2. Install PHP dependencies
   composer install

3. Install frontend dependencies and build assets
   npm install
   npm run dev
   composer run dev
   (or `npm run build` for production)

4. Environment
   cp .env.example .env
   Edit `.env` and set your database and other environment variables.

5. App key & storage
   php artisan key:generate
   php artisan storage:link

6. Database
   php artisan migrate
   php artisan db:seed   # optional, if seeders exist

7. Serve the app
   php artisan serve
   Visit http://localhost:8000

## Running tests
Run the test suite:
php artisan test

## Common tips
- To run queues: php artisan queue:work
- To run scheduler locally: php artisan schedule:run
- Use `.env` to configure mail, third-party services, and storage drivers.

## Contributing
Contributions are welcome. Please fork the repository, make your changes on a feature branch, and open a pull request with a clear description of your changes.

## License
MIT — see LICENSE file for details.

## Contact
Project maintained by fortune-c — open issues or pull requests in the repository: https://github.com/fortune-c/chipper

