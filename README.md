# Online Discussion Forum

A web-based platform allowing members of the general public to register, participate in structured discussions, and engage with community-generated content.

## Overview

The system is organized into a three-level hierarchy: **Categories** contain **Threads**, and **Threads** contain **Posts**.

## Technology Stack

- **Backend:** Laravel (PHP)
- **Database:** MySQL
- **Deployment:** XAMPP (local), with migration path to VPS
- **Scale:** Designed for up to 100 concurrent users

## Features

- User authentication and profile management
- Forum structure management (categories, threads, posts)
- Upvote and downvote voting on posts
- Three-tier role system: Administrator, Moderator, Regular User
- Read-only guest access without registration
- Unlimited post editing by the original author
- User @mentions within posts
- Post flagging and moderator review queue
- Full-text search across threads and posts
- In-app notifications
