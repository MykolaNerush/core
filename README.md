# 📺 Core Streaming Service

**Core** is a pet project for a streaming service, built on Symfony 7.2 using **DDD (Domain-Driven Design)**. The project supports video uploading, monetization, playlist management, comments, subscriptions, ratings, and advertisements.

---

## 🚀 Technologies
- ⚡ **Symfony 7.2** — Modern PHP framework
- 🏗 **DDD (Domain-Driven Design)** — Clear architecture
- 🐳 **Docker** — Environment isolation
- 🐘 **MySQL** — Data storage
- 📡 **RabbitMQ** — Event and queue processing
- 🔥 **Redis** — Caching

---

## 🏛 Core Entities

### 📹 Video (`Video`)
| Field                | Type       | Description |
|--------------------|----------|-------------|
| `id` | `PK` | Unique identifier |
| `title` | `string` | Video title |
| `description` | `text` | Video description |
| `file_path` | `string` | File path |
| `thumbnail_path` | `string` | Thumbnail path |
| `uploader_id` | `FK to User` | User ID |
| `duration` | `int` | Video duration |
| `views_count` | `int` | View count |
| `status` | `enum` | `public`, `private`, `unlisted`, `deleted` |
| `region_restrictions` | `JSON` | Region restrictions |
| `created_at` | `datetime` | Creation date |
| `updated_at` | `datetime` | Update date |

### 💬 Comment (`Comment`)
| Field | Type | Description |
|------|----|-------------|
| `id` | `PK` | Unique ID |
| `video_id` | `FK to Video` | Video reference |
| `user_id` | `FK to User` | User reference |
| `content` | `text` | Comment text |
| `status` | `enum` | `visible`, `hidden`, `reported` |
| `created_at` | `datetime` | |
| `updated_at` | `datetime` | |

### 📜 Playlist (`Playlist`)
| Field | Type | Description |
|------|----|-------------|
| `id` | `PK` | Unique ID |
| `title` | `string` | Playlist title |
| `user_id` | `FK to User` | Owner |
| `is_public` | `bool` | Public or private |
| `created_at` | `datetime` | |
| `updated_at` | `datetime` | |

### ⭐ Rating (`Rating`)
| Field | Type | Description |
|------|----|-------------|
| `id` | `PK` | Unique ID |
| `video_id` | `FK to Video` | Video reference |
| `user_id` | `FK to User` | User reference |
| `rating` | `int (1-5)` | Rating score |
| `created_at` | `datetime` | |

### 🔔 Subscription (`Subscription`)
| Field | Type | Description |
|------|----|-------------|
| `id` | `PK` | Unique ID |
| `follower_id` | `FK to User` | Subscriber |
| `following_id` | `FK to User` | Followed user |
| `created_at` | `datetime` | |

---

## 📡 API Endpoints

### 🏠 Users (`/api/v1/internal/users`)
| Method | URL | Description |
|-------|-----|-------------|
| `GET` | `/api/v1/internal/users` | Get users list |
| `POST` | `/api/v1/internal/users` | Create user |
| `GET` | `/api/v1/internal/users/{uuid}` | Get user by UUID |
| `POST` | `/api/v1/internal/users/{uuid}` | Update user |
| `DELETE` | `/api/v1/internal/users/{uuid}` | Delete user |

### ✅ Health Check (`/api/v1/internal/healthz`)
| Method | URL | Description |
|-------|-----|-------------|
| `GET` | `/api/v1/internal/healthz` | API health check |

---

## 🎯 Future Plans
- ✅ Add live streaming
- ✅ Background video processing
- ✅ Auto-generated subtitles
- ✅ WebRTC support for live broadcasts

## 🎓 Installation
```bash
git clone https://github.com/your-repo/core.git
cd core
composer install
docker-compose up -d
```

---

## 🙌 Contributing
If you want to contribute — open a Pull Request or submit an [Issue](https://github.com/MykolaNerush/core/issues). We welcome your ideas! 🚀

