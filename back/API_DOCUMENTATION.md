# Waqitly API Documentation

## Overview
This document outlines the complete CRUD API endpoints for the Waqitly space booking system.

## Base URL
```
http://your-domain.com/api/v1
```

## Response Format
All API responses follow this standard format:
```json
{
    "success": true|false,
    "data": {...},
    "message": "Success/Error message",
    "errors": {...} // Only present on validation errors
}
```

---

## 1. Categories API

### GET /categories
**Description**: Get all categories with their associated spaces  
**Response**: Array of categories

### POST /categories
**Description**: Create a new category  
**Body**:
```json
{
    "name": "Meeting Rooms",
    "image": "https://example.com/image.jpg"
}
```

### GET /categories/{id}
**Description**: Get specific category with spaces and organization details

### PUT/PATCH /categories/{id}
**Description**: Update a category  
**Body**: Same as POST (fields optional)

### DELETE /categories/{id}
**Description**: Delete a category (fails if has associated spaces)

---

## 2. Organizations API

### GET /organizations
**Description**: Get all organizations with filtering  
**Query Parameters**:
- `search` - Search in name/description

### POST /organizations
**Description**: Create a new organization  
**Body**:
```json
{
    "name": "Tech Hub",
    "owner_id": 1,
    "description": "Modern workspace",
    "email": "contact@techhub.com",
    "image": "https://example.com/logo.jpg",
    "locations": [
        {
            "latitude": 33.270713,
            "longitude": 44.374251,
            "address": "Baghdad, Iraq"
        }
    ]
}
```

### GET /organizations/{id}
**Description**: Get specific organization with all relationships

### PUT/PATCH /organizations/{id}
**Description**: Update organization and locations

### DELETE /organizations/{id}
**Description**: Delete organization (fails if has spaces)

### GET /organizations/{id}/spaces
**Description**: Get all spaces belonging to an organization

---

## 3. Spaces API

### GET /spaces
**Description**: Get spaces with advanced filtering  
**Query Parameters**:
- `search` - Search in name/description
- `category_id` - Filter by category
- `organization_id` - Filter by organization
- `min_capacity`, `max_capacity` - Capacity range
- `min_price`, `max_price` - Price range (per hour)
- `latitude`, `longitude`, `radius` - Location-based search (radius in km)
- `sort_by` - Sort by: name, price_per_hour, capacity, created_at
- `sort_order` - asc/desc
- `per_page` - Pagination (default: 12)

### POST /spaces
**Description**: Create a new space  
**Body**:
```json
{
    "name": "Conference Room A",
    "description": "Large meeting room",
    "size": 50,
    "capacity": 20,
    "floor": "2nd Floor",
    "price_per_hour": 25000,
    "thumbnail": "https://example.com/thumb.jpg",
    "category_id": 1,
    "organization_id": 1,
    "locations": [
        {
            "latitude": 33.270713,
            "longitude": 44.374251,
            "location_written": "Building A, Floor 2"
        }
    ],
    "images": [
        {
            "image": "https://example.com/image1.jpg",
            "low_res_image": "https://example.com/thumb1.jpg"
        }
    ],
    "services": [
        {
            "service_id": 1,
            "price": 5000
        }
    ]
}
```

### GET /spaces/{id}
**Description**: Get space details with ratings and reviews

### PUT/PATCH /spaces/{id}
**Description**: Update space information

### DELETE /spaces/{id}
**Description**: Delete a space

### GET /spaces/{id}/stats
**Description**: Get rating statistics for a space

---

## 4. Services API

### GET /services
**Description**: Get all services

### POST /services
**Description**: Create a new service  
**Body**:
```json
{
    "name": "WiFi"
}
```

### GET /services/{id}
**Description**: Get service with associated spaces

### PUT/PATCH /services/{id}
**Description**: Update service name

### DELETE /services/{id}
**Description**: Delete service (fails if associated with spaces)

---

## 5. Bookings API

### GET /bookings
**Description**: Get bookings with filtering  
**Query Parameters**:
- `user_id` - Filter by user
- `space_id` - Filter by space
- `start_date`, `end_date` - Date range filter

### POST /bookings
**Description**: Create a new booking  
**Body**:
```json
{
    "user_id": 1,
    "space_id": 1,
    "date": "2025-12-25",
    "start_time": "09:00:00",
    "end_time": "17:00:00",
    "total_price": 200000,
    "services": [
        {
            "space_service_id": 1,
            "quantity": 2,
            "price": 10000
        }
    ]
}
```

### GET /bookings/{id}
**Description**: Get booking details

### PUT/PATCH /bookings/{id}
**Description**: Update booking (only future bookings)

### DELETE /bookings/{id}
**Description**: Cancel booking (only future bookings)

---

## 6. Reservations API

### GET /reservations
**Description**: Get reservations with filtering  
**Query Parameters**:
- `user_id` - Filter by user
- `space_id` - Filter by space
- `status` - Filter by status (pending, confirmed, cancelled, completed)
- `start_date`, `end_date` - Date range filter

### POST /reservations
**Description**: Create a new reservation  
**Body**:
```json
{
    "user_id": 1,
    "space_id": 1,
    "date": "2025-12-25",
    "start_time": "09:00:00",
    "end_time": "17:00:00",
    "total_price": 200000,
    "details": [
        {
            "description": "Special Requirements",
            "value": "Need projector setup"
        }
    ]
}
```

### GET /reservations/{id}
**Description**: Get reservation details

### PUT/PATCH /reservations/{id}
**Description**: Update reservation status or details

### DELETE /reservations/{id}
**Description**: Delete reservation

### PATCH /reservations/{id}/cancel
**Description**: Cancel a reservation

---

## 7. Reviews API

### GET /reviews
**Description**: Get reviews with filtering  
**Query Parameters**:
- `user_id` - Filter by user
- `space_id` - Filter by space
- `min_rating` - Minimum rating filter

### POST /reviews
**Description**: Create a review  
**Body**:
```json
{
    "rating": 5,
    "review": "Excellent space with great amenities!",
    "user_id": 1,
    "space_id": 1
}
```

### GET /reviews/{id}
**Description**: Get review details

### PUT/PATCH /reviews/{id}
**Description**: Update review

### DELETE /reviews/{id}
**Description**: Delete review

---

## Error Codes

- **200**: Success
- **201**: Created
- **404**: Not Found
- **422**: Validation Error
- **500**: Server Error

## Features Implemented

### ✅ Complete CRUD Operations
- **Categories**: Full CRUD with relationship management
- **Organizations**: CRUD with location management
- **Spaces**: Advanced CRUD with images, services, and location filtering
- **Services**: Basic CRUD with relationship checks
- **Bookings**: CRUD with availability checking and time conflict prevention
- **Reservations**: CRUD with status management and cancellation
- **Reviews**: CRUD with statistics and duplicate prevention

### ✅ Advanced Features
- **Geolocation Search**: Find spaces within a radius
- **Availability Checking**: Prevent double bookings
- **Relationship Management**: Proper foreign key handling
- **Validation**: Comprehensive input validation
- **Error Handling**: Consistent error responses
- **Filtering & Sorting**: Advanced query capabilities
- **Pagination**: Efficient data loading
- **Statistics**: Rating analytics for spaces

### ✅ Data Integrity
- Prevents deletion of entities with dependencies
- Validates time ranges and conflicts
- Enforces business rules (e.g., no past booking updates)
- Maintains referential integrity

## Usage Examples

### Create a Space with Full Details
```bash
curl -X POST http://localhost:8001/api/v1/spaces \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Executive Meeting Room",
    "description": "Premium meeting space with city view",
    "capacity": 12,
    "price_per_hour": 50000,
    "category_id": 1,
    "organization_id": 1,
    "locations": [{"latitude": 33.27, "longitude": 44.37, "location_written": "Floor 5"}],
    "services": [{"service_id": 1, "price": 10000}]
  }'
```

### Search Spaces by Location
```bash
curl "http://localhost:8001/api/v1/spaces?latitude=33.27&longitude=44.37&radius=5&min_capacity=10"
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
    "total_price": 200000
  }'
```

This API provides a complete backend solution for a space booking platform with all necessary CRUD operations and business logic!
