# social-media-website

The list of **API features** that can be implemented. The features include **user management**, **posts and comments**, **groups**, and **other functionality** such as notifications, AI integration, search, and more.

---

### Eid TASKS 2: 
  - |x| cache
  - | | create tags by #?_? in text (search text) => regex , in job , queue driver 
  - | | auto complete when search about user. (min=2chars, ranking=popular show first, cache)
  - | | popular tags today
  - | | server side events (implement home page).
  - | | return type methods, attr types. declare(strict_types=1);
  - | | assign badge .. job

---

- | | use php-stan.

### Eid Code Review: TASKS 1: `Done`
  - |x| Create Services and use in controllers.
  - |x| HomeController -> Get posts.
  - |x| SoftDelete -> Post.
  - |x| user profile. 
  - |x| resources -> integer data cast.
  - |x| use db transaction in Group->store.
  - |x| post owner can delete comment.
  

---

### User Management APIs: `(Done)`
  - |x| User Registration: +(auto-generate a unique username).
  - |x| Email Verification.
  - |x| Login
  - |x| Get User Profile Information
  - |x| Update User Profile
  - |x| Follow/Unfollow User: +Send notifications when following.
  - |x| Get Followers & Following

---

### Post Management APIs: `(Done)`
  - |x| Create Post.
  - |x| Get All Posts (Home Feed): from users the user is following and groups the user is a member of.
  - |x| Update Post.
  - |x| Delete Post
  - |x| Like/Unlike Post
  - |x| Post Comments (can be nested)
  - |x| Edit/Delete Comment

---

### Group Management APIs: `(Done)`
  - |x| Create Group
  - |x| Get Group Details:  (posts, description).
  - |x| Get Group Users
  - |x| Update Group (only owner)
  - |x| Delete Group (only owner)
  - |x| Join Group: (Request to join if manual approval is required) + Notify group admins.
  - |x| Approve/Reject Group Request
  - |x| Add/Remove Admin: Notify the user when their role is changed.
  - |x| Group Posts: Only group members can post.
  - |x| Delete Group Post:  Group admins can delete posts(Notify the owner when their post is removed).

---

### Search & Hashtags & Filter APIs: `(Done)`
  - |x| Search with tags
  - |x| Content Filtering (by popularity = sort by likes and comments).
- |x| Full-Text Search for posts: using Scout.

- try: Implement fuzzy search and rank search results based on relevance

---

### Advanced User Interaction `(Done)`
  - |x| User Badges awarding based on number of posts. 

---

### Profile Avatar Management
  - use: AWS S3.
  - update.
  - delete.

---

### Enhanced Notification System
  - Allow users to subscribe to specific types of notifications (e.g., follow notifications, group notifications). `POST /api/notifications/subscribe`
= Subscriber Observer Design Pattern

---

### Social Graph Analysis `skip`

- Social Graph API
    - API Endpoint: `GET /api/users/{user_id}/social-graph`
    - Implement graph analysis to visually represent relationships (followers, followings) between users, groups, and content.
    - Advanced Feature: Implement graph algorithms like PageRank to determine influential users in the network, or community detection algorithms to group similar users.

- Suggest New Friends
  - API Endpoint: `GET /api/users/{user_id}/suggestions`
  - Based on the user’s social graph, suggest users they may want to follow (e.g., friends of friends, users in similar groups).

---

### Media & Attachments `Skip`

- Advanced Video Handling (Streaming)
    - `GET /api/posts/{post_id}/video-stream`
    - Allow video streaming directly from the platform (similar to YouTube or Instagram). Integrate a video transcoding service for handling different formats and resolutions.
    - Advanced Feature: Integrate adaptive bitrate streaming (ABR) to optimize video playback based on user bandwidth.

---

