# Waqitly API Documentation

This folder contains comprehensive documentation for the Waqitly space booking API.

## 📁 Files Overview

### 1. `API_ENDPOINTS.md`
**Complete API Documentation**
- Detailed endpoint descriptions
- Request/response examples
- Validation rules
- Query parameters
- Error handling
- Testing examples with cURL

### 2. `Waqitly_API.postman_collection.json`
**Postman Collection**
- Ready-to-import Postman collection
- All 38 endpoints configured
- Sample request bodies
- Environment variables setup
- Automated tests included

### 3. `api_docs.html`
**Interactive HTML Documentation**
- Beautiful web interface
- Color-coded HTTP methods
- Quick navigation
- Testing examples
- Responsive design

## 🚀 Quick Start

### Option 1: View HTML Documentation
Open `api_docs.html` in your web browser for a beautiful, interactive documentation experience.

### Option 2: Import Postman Collection
1. Open Postman
2. Click "Import"
3. Select `Waqitly_API.postman_collection.json`
4. Set environment variable `base_url` to `http://localhost:8001`
5. Start testing!

### Option 3: Use Markdown Documentation
Read `API_ENDPOINTS.md` for detailed technical documentation with examples.

## 📋 API Summary

**Base URL:** `http://your-domain.com/api/v1`

**Total Endpoints:** 38

**Resources:**
- **Categories** (5 endpoints)
- **Organizations** (6 endpoints)
- **Spaces** (7 endpoints)
- **Services** (5 endpoints)
- **Bookings** (5 endpoints)
- **Reservations** (6 endpoints)
- **Reviews** (5 endpoints)

## ✅ Features

### Complete CRUD Operations
- ✅ Create, Read, Update, Delete for all resources
- ✅ Advanced filtering and search
- ✅ Relationship management
- ✅ Validation and error handling

### Advanced Features
- 🌍 **Geolocation Search** - Find spaces within radius
- 📅 **Availability Checking** - Prevent double bookings
- ⭐ **Rating System** - Reviews with statistics
- 🏢 **Multi-location Support** - Organizations and spaces
- 📊 **Advanced Filtering** - Price, capacity, date ranges
- 🔍 **Full-text Search** - Name and description search

### Business Logic
- Time conflict prevention for bookings
- Status management for reservations
- Relationship integrity (prevent deletion with dependencies)
- One review per user per space
- Future-only booking modifications

## 🧪 Testing Examples

### Create a Space
```bash
curl -X POST http://localhost:8001/api/v1/spaces \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Conference Room A",
    "capacity": 20,
    "price_per_hour": 25000,
    "category_id": 1,
    "organization_id": 1
  }'
```

### Search by Location
```bash
curl "http://localhost:8001/api/v1/spaces?latitude=33.27&longitude=44.37&radius=5"
```

### Book a Space
```bash
curl -X POST http://localhost:8001/api/v1/bookings \
  -H "Content-Type: application/json" \
  -d '{
    "user_id": 1,
    "space_id": 1,
    "date": "2025-12-25",
    "start_time": "14:00:00",
    "end_time": "18:00:00",
    "total_price": 100000
  }'
```

## 🔧 Development

### Prerequisites
- Laravel 10+
- PHP 8.1+
- MySQL/SQLite database
- Composer

### Setup
1. Run migrations: `php artisan migrate`
2. Seed database: `php artisan db:seed` (if available)
3. Start server: `php artisan serve`
4. Test endpoints using provided documentation

## 📝 Response Format

All endpoints return consistent JSON responses:

```json
{
    "success": true|false,
    "data": {...},
    "message": "Operation message",
    "errors": {...} // Only on validation errors
}
```

## 🚨 Error Codes
- **200** - Success
- **201** - Created
- **404** - Not Found
- **422** - Validation Error
- **500** - Server Error

---

**Ready to use!** 🎉 

Choose your preferred documentation format and start building amazing applications with the Waqitly API!
