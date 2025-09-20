# 🏢 Waqitly API - Space Booking System

A comprehensive RESTful API for space booking and management built with Laravel.

## 🚀 Quick Start

### 1. Start the Server
```bash
php artisan serve --host=0.0.0.0 --port=8001
```

### 2. View Documentation
**🌐 Interactive Documentation (Home Page):**
```
http://localhost:8001/
```

**📚 Alternative Documentation Route:**
```
http://localhost:8001/api/docs
```

**🔗 Direct API Access:**
```
http://localhost:8001/api/v1/
```

## 📋 API Overview

- **Base URL:** `http://localhost:8001/api/v1`
- **Total Endpoints:** 38
- **Content-Type:** `application/json`
- **Authentication:** Not required (for development)

## 🎯 Available Resources

| Resource | Endpoints | Description |
|----------|-----------|-------------|
| **Categories** | 5 | Space categories management |
| **Organizations** | 6 | Organization and location management |
| **Spaces** | 7 | Space listings with advanced search |
| **Services** | 5 | Available services management |
| **Bookings** | 5 | Space booking with availability checking |
| **Reservations** | 6 | Reservation management with status tracking |
| **Reviews** | 5 | Rating and review system |

## 🌟 Key Features

- ✅ **Complete CRUD Operations** for all resources
- 🌍 **Geolocation Search** - Find spaces within radius
- 📅 **Availability Checking** - Prevent double bookings
- ⭐ **Rating System** - Reviews with detailed statistics
- 🔍 **Advanced Filtering** - Search by multiple parameters
- 📊 **Pagination Support** - Efficient data loading
- 🛡️ **Input Validation** - Comprehensive validation rules
- 🚨 **Error Handling** - Consistent error responses

## 🧪 Quick API Test

```bash
# Get all categories
curl http://localhost:8001/api/v1/categories

# Get all spaces with pagination
curl http://localhost:8001/api/v1/spaces?per_page=5

# Search spaces by location (within 5km radius)
curl "http://localhost:8001/api/v1/spaces?latitude=33.270713&longitude=44.374251&radius=5"

# Create a new category
curl -X POST http://localhost:8001/api/v1/categories \
  -H "Content-Type: application/json" \
  -d '{"name": "Meeting Rooms", "image": "https://example.com/image.jpg"}'
```

## 📚 Documentation Files

- **`/docs/API_ENDPOINTS.md`** - Complete technical documentation
- **`/docs/api_docs.html`** - Interactive web documentation (served as home page)
- **`/docs/Waqitly_API.postman_collection.json`** - Postman collection for testing
- **`/docs/README.md`** - Documentation overview

## 🔧 Development Setup

### Prerequisites
- PHP 8.1+
- Composer
- Laravel 10+
- SQLite/MySQL database

### Installation
```bash
# Install dependencies
composer install

# Setup environment
cp .env.example .env
php artisan key:generate

# Run migrations
php artisan migrate

# Seed database (optional)
php artisan db:seed

# Start development server
php artisan serve --host=0.0.0.0 --port=8001
```

### Database Configuration
The project is configured to use SQLite by default. The database file is located at:
```
database/database.sqlite
```

## 🌐 Live Demo

Once the server is running, visit:
- **Documentation:** http://localhost:8001/
- **API Base:** http://localhost:8001/api/v1
- **Sample Endpoint:** http://localhost:8001/api/v1/categories

## 📱 Postman Testing

1. Import `docs/Waqitly_API.postman_collection.json`
2. Set environment variable `base_url` to `http://localhost:8001`
3. Run the collection to test all endpoints

## 🏗️ Architecture

### Controllers
- `CategoryController` - Category management
- `OrganizationController` - Organization and location management
- `SpaceController` - Space management with advanced search
- `ServiceController` - Service management
- `BookingController` - Booking management with availability checking
- `ReservationController` - Reservation management with status tracking
- `RatingReviewController` - Review and rating system

### Key Features Implementation
- **Geolocation Search** - Haversine formula for radius-based search
- **Availability Checking** - Time conflict detection for bookings
- **Relationship Management** - Proper foreign key handling
- **Validation** - Comprehensive input validation
- **Error Handling** - Consistent JSON error responses

## 📞 API Response Format

### Success Response
```json
{
    "success": true,
    "data": {...},
    "message": "Operation completed successfully"
}
```

### Error Response
```json
{
    "success": false,
    "message": "Error description",
    "errors": {...} // Only on validation errors
}
```

## 🚀 Production Deployment

### Security Considerations
- Add authentication middleware
- Implement rate limiting
- Configure CORS properly
- Validate file uploads
- Use HTTPS in production

### Performance Optimization
- Add database indexes
- Implement caching
- Optimize database queries
- Add response caching

---

**🎉 Ready to use!** Visit http://localhost:8001/ to start exploring the API documentation.

Built with ❤️ using Laravel