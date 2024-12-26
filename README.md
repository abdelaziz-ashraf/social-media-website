# social-media-website

The list of **API features** that can be implemented. The features include **user management**, **posts and comments**, **groups**, and **other functionality** such as notifications, AI integration, search, and more.

---

### **1. User Management APIs**

- |x| User Registration: +(auto-generate a unique username).
- |x| Email Verification.
- |x| Login
- |x| Get User Profile Information
- |x| Update User Profile
- |x| Follow/Unfollow User: +Send notifications when following.
- |x| Get Followers & Following

---

### **2. Post Management APIs**

- |x| Create Post
- |x| Get All Posts (Home Feed): from users the user is following.
    - todo: load from groups the user is a member of.
    - todo: Cache the posts.
- |x| Update Post.
- |x| Delete Post
    - todo: group admin can delete it (Notify the owner when a post is deleted by the admin).
- |x| Like/Unlike Post

- **Post Attachments**
    - `POST /api/posts/{post_id}/attachments`
    - Upload attachments to the post (e.g., images, documents).
    - **Validation**: File size, type, and preview for images.

- **Post Comments**
    - `POST /api/posts/{post_id}/comments`: Add a comment to a post (can be nested).
    - **Like/Unlike Comment**: `POST /api/comments/{comment_id}/like`
    - **Edit/Delete Comment**: `PUT /api/comments/{comment_id}/edit`, `DELETE /api/comments/{comment_id}/delete`

- **Get Comments for Post**
    - `GET /api/posts/{post_id}/comments`

---

### **3. Group Management APIs**

- **Create Group**
    - `POST /api/groups`: (with name, description, and settings (auto approval or manual approval)).
    - **Validation**: Unique group name.

- **Get Group Details**
    - `GET /api/groups/{group_id}`: (posts, users, description).

- **Update Group**
    - `PUT /api/groups/{group_id}`

- **Join/Request to Join Group**
    - `POST /api/groups/{group_id}/join`
    - Request to join a group (if manual approval is required).
    - **Notifications**: Notify group admins when a user requests to join.

- **Approve/Reject Group Request**
    - `POST /api/groups/{group_id}/requests/{user_id}/approve`
    - `POST /api/groups/{group_id}/requests/{user_id}/reject`
    - Approve or reject a user’s request to join a group.

- **Invite User to Group**
    - `POST /api/groups/{group_id}/invite`:  by email or username.
    - **Notifications**: Send email invitations with a link.

- **Add/Remove Admin**
    - `POST /api/groups/{group_id}/admin/{user_id}`
    - **Notifications**: Notify the user when their role is changed.

- **Group Posts**
    - `POST /api/groups/{group_id}/posts`: Create a post within a group.
    - **Permissions**: Only group members can post.

- **Delete Group Post**
    - `DELETE /api/groups/{group_id}/posts/{post_id}`
    - Group admins can delete posts from the group.
    - **Notifications**: Notify the post owner when their post is removed.

---

### **4. Notification APIs**

- **Send Notification**
    - `POST /api/notifications`
    - Send notifications to users for actions like following, post likes, comments, and group invitations.

- **Get Notifications**
    - `GET /api/notifications`
    - Retrieve notifications for the logged-in user.

- **Mark Notifications as Read**
    - `POST /api/notifications/{notification_id}/read`
    - Mark notifications as read.

---

### **5. AI and External APIs**

- **Generate Post Description with OpenAI**
    - `POST /api/ai/generate-description`
    - Use OpenAI API to generate descriptions for posts.
    - Request body: `{ "text": "User’s post content" }`

---

### **6. Search & Hashtags APIs**

- **Search Users, Groups, and Posts**
    - `GET /api/search`: by keywords.

- **Search with Hashtags**
    - `GET /api/hashtags/{hashtag}/posts`: Get posts that contain a specific hashtag.

---

### **Additional Considerations**

- **Data Caching**:
    - Use caching (e.g., Redis) to store frequently accessed data such as posts, comments, and group information to speed up response times.

- **Throttling/Rate Limiting**:
    - Implement rate limiting for APIs that may be abused (e.g., posting, commenting).

- **Real-time Updates**:
    - Implement WebSockets or Laravel Echo for real-time updates when new posts, comments, or likes happen, and when users join groups.

---

## **Advanced Features**

---

### **1. Advanced Post & Content Management**

- **Content Moderation with AI**
    - **API Endpoint**: `POST /api/posts/{post_id}/moderate`
    - Automatically analyze post content for inappropriate language using external APIs like Google Perspective API. Flag posts for review or automatically reject posts that don’t meet guidelines.

- **Content Filtering** x
    - **API Endpoint**: `GET /api/posts?filter=popular`
    - Implement advanced filtering options for posts (e.g., by popularity, engagement, or custom filters).
    - **Feature**: Add the ability to filter posts based on categories or tags, and filter out specific types of content (e.g., NSFW, advertisements).

---

### **2. Advanced User Interaction**

- **User Badges & Achievements**
    - **API Endpoint**: `GET /api/users/{user_id}/badges`
    - Provide users with badges based on their activity (e.g., "Top Contributor", "Verified User", "Early Adopter").
    - **Advanced Feature**: Automate badge awarding based on user milestones and achievements (e.g., number of posts, followers, likes, etc.).

- **Time-based Content (Ephemeral Content)**
    - **API Endpoint**: `POST /api/posts/ephemeral`
    - Allow users to post content that disappears after a certain amount of time (e.g., Instagram Stories or Snapchat).
    - **Advanced Feature**: Track and notify users when their ephemeral content is about to expire.

---

### **3. Enhanced Notification System**

- **Real-time Notifications (WebSockets & Push)**
    - **API Endpoint**: `GET /api/notifications`
    - **Push Notifications**: Integrate Firebase Cloud Messaging (FCM) to send **push notifications** to mobile or web users in real-time.

- **User Subscription to Notifications**
    - **API Endpoint**: `POST /api/notifications/subscribe`
    - Allow users to subscribe to specific types of notifications (e.g., follow notifications, group notifications).
    - **Advanced Feature**: Implement **notification preferences** that allow users to choose which events they want to be notified about (e.g., posts, comments, mentions).
    - Subscriber Observer Design Pattern 
---

### **4. Advanced Search & Discovery**

- **Advanced Search with Full-Text Search**
    - **API Endpoint**: `GET /api/search`
    - Integrate **full-text search** for posts, users, and groups. Implement advanced search features like fuzzy search, ranking search results based on relevance, and search with filters (e.g., hashtags, date, content type).
    - **Advanced Feature**: Implement **Scout**.

---

### **5. Social Graph Analysis**
skip now .. 
- **Social Graph API**
    - **API Endpoint**: `GET /api/users/{user_id}/social-graph`
    - Implement **graph analysis** to visually represent relationships (followers, followings) between users, groups, and content.
    - **Advanced Feature**: Implement **graph algorithms** like **PageRank** to determine influential users in the network, or **community detection** algorithms to group similar users.

- **Suggest New Friends/Connections**
    - **API Endpoint**: `GET /api/users/{user_id}/suggestions`
    - Based on the user’s social graph, suggest users they may want to follow (e.g., friends of friends, users in similar groups).
    - **Advanced Feature**: Implement **machine learning models** for better friend suggestions based on common interests and activities.

---

### **6. AI-Powered Features**

- **AI-Generated Content Summaries**
    - **API Endpoint**: `GET /api/posts/{post_id}/summary`
    - Use OpenAI or similar services to generate summaries for long posts.
    - **Advanced Feature**: Provide **auto-suggestions** for post titles based on the content.

---

### **7. Media & Attachments**

- **Advanced Video Handling (Streaming)**
    - **API Endpoint**: `GET /api/posts/{post_id}/video-stream`
    - Allow video streaming directly from the platform (similar to YouTube or Instagram). Integrate a **video transcoding service** for handling different formats and resolutions.
    - **Advanced Feature**: Integrate **adaptive bitrate streaming** (ABR) to optimize video playback based on user bandwidth.
    - Skip now (eid)

- **Cloud Storage Integration for Media**
    - **API Endpoint**: `POST /api/posts/{post_id}/media`
    - Implement cloud storage (AWS S3, Google Cloud Storage) for storing large media files like photos, videos, and documents. Ensure that users can upload, access, and delete media easily.
    - **Caching**: Use caching to optimize media loading times.
---
