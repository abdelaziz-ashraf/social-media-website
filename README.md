# Social Media Website

## Overview
A social media platform with comprehensive features, including user management, posts and comments, groups, notifications, search functionality, and more. Below is a detailed list of features and corresponding API endpoints.

---

## Features & Progress

### **User Management**
- **User Registration**: Auto-generates a unique username.
- **Email Verification**.
- **Login**.
- **User Profile Management**:
    - Get profile information.
    - Update profile.
    - Follow/unfollow users (with notifications).
    - View followers and following lists.

### **Post Management**
- Create, update, delete posts.
- Home feed: Fetch posts from followed users and joined groups.
- Like/unlike posts.
- Post comments (nested, editable, deletable).

### **Group Management**
- Create, update, delete groups (owner only).
- View group details and members.
- Join groups (manual approval supported).
- Approve/reject join requests.
- Assign/remove admin roles (with notifications).
- Post management within groups (admins can delete posts).

### **Search, Hashtags, & Filters**
- Search by tags.
- Search by user's name
- Sort content by popularity.
- Full-text search for posts using Laravel Scout.

### **Advanced User Interaction**
- Award badges to users based on post count.

### **Profile Avatar Management**
- Use AWS S3 for avatar uploads.
- Update and delete avatars.

### **Notification System**
- Subscribe to another user post notifications.

---

## API Routes

### **Authentication**
```php
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('verify-email', [AuthController::class, 'verifyEmail']);
});
```

### **User Management**
```php
Route::middleware(['auth:sanctum'])->group(function () {
    Route::put('users/profile', [ProfileController::class, 'update']);
    Route::prefix('users/{user}')->group(function () {
        Route::get('profile', [ProfileController::class, 'profile']);
        Route::get('followers', [ProfileController::class, 'followers']);
        Route::get('followings', [ProfileController::class, 'followings']);
    });

    Route::post('users/{userToFollow}/follow', [ProfileController::class, 'follow']);
    Route::post('users/{userToUnfollow}/unfollow', [ProfileController::class, 'unfollow']);
});
```

### **Home Feed**
```php
Route::prefix('home')->group(function () {
    Route::get('/feed', [HomeController::class, 'feed']);
});
```

### **Post Management**
```php
Route::prefix('posts')->group(function () {
    Route::get('{post}', [PostController::class, 'show']);
    Route::post('/', [PostController::class, 'store']);
    Route::put('{post}', [PostController::class, 'update']);
    Route::delete('{post}', [PostController::class, 'destroy']);
    Route::put('{post}/like', [PostController::class, 'toggleLike']);
    Route::post('{post}/comments', [CommentController::class, 'store']);
});
```

### **Comments Management**
```php
Route::prefix('comments')->group(function () {
    Route::put('{comment}', [CommentController::class, 'update']);
    Route::delete('{comment}', [CommentController::class, 'destroy']);
});
```

### **Group Management**
```php
Route::prefix('groups')->group(function () {
    Route::get('/', [GroupController::class, 'index']);
    Route::get('{group}', [GroupController::class, 'show']);
    Route::get('/{group}/members', [GroupController::class, 'members']);
    Route::post('/', [GroupController::class, 'store']);
    Route::put('{group}', [GroupController::class, 'update']);
    Route::delete('{group}', [GroupController::class, 'destroy']);
    Route::post('{group}/join', [GroupController::class, 'join']);
    Route::post('{group}/approve/{user}', [GroupController::class, 'approveRequest']);
    Route::post('{group}/reject/{user}', [GroupController::class, 'rejectRequest']);
    Route::put('{group}/update-admin-role/{userId}', [GroupController::class, 'updateAdminRole']);
});
```

### **Search Functionality**
```php
Route::prefix('search')->group(function () {
    Route::get('tags/{tag}', [SearchController::class, 'tagSearch']);
    Route::get('full-text/{text}', [SearchController::class, 'fullTextSearch']);
    Route::get('users', [SearchController::class, 'userSearch']);
});
```

