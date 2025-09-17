// types.ts
// Server-layer TypeScript types for Waqitly v2 schema
// -----------------------------------------------------------------------------
// Conventions:
// - IDs are `number` (SERIAL in DB).
// - Dates/times are ISO strings (use Date at the edges if you prefer).
// - Postgres `point` is `[number, number]`.
// - Monetary/price fields are `number` (integers in DB).
// -----------------------------------------------------------------------------

// Core primitives
export type ID = number;
export type ISODateTime = string; // e.g., '2025-09-16T09:00:00Z'
export type ISODate = string;     // e.g., '2025-09-16'
export type ISOTime = string;     // e.g., '09:30:00'
export type PgPoint = [number, number];

// Enums (DB enums)
export enum Role {
  SuperAdmin = "super_admin",
  Admin = "admin",
  Staff = "staff",
  User = "user",
}

export enum ReservationStatus {
  Reserved = "reserved",
  Pending = "pending",
  Rejected = "rejected",
  Cancelled = "cancelled",
  Done = "done",
}

// ------------------------------------
// Users & Auth
// ------------------------------------
export interface User {
  id: ID;
  full_name: string | null;
  username: string | null;
  email: string | null;
  image: string | null;
  bio: string | null;
  role: Role;
  gender: string | null;
  birth_date: ISODate | null;
  created_at: ISODateTime;
  updated_at: ISODateTime;
}

export interface LocalAuth {
  user_id: ID;
  phone_number: string | null;
  password_hash: string;
  is_phone_verified: boolean;
}

export interface OAuth {
  user_id: ID;
  provider: string;      // e.g., 'google', 'facebook'
  provider_id: string;   // uuid in DB
}

export interface Session {
  id: ID;
  user_id: ID;
  revoked: boolean;
  user_agent: string | null;
  refresh_token_hash: string;
  expires_at: ISODateTime;
  created_at: ISODateTime;
  updated_at: ISODateTime;
}

export interface PhoneVerificationCode {
  id: ID;
  user_id: ID;
  otp_code: string;
  expires_at: ISODateTime;
  created_at: ISODateTime;
}

export interface PasswordReset {
  id: ID;
  user_id: ID;
  otp_code: string;
  expires_at: ISODateTime;
  created_at: ISODateTime;
}

// ------------------------------------
// Catalog & Organizations
// ------------------------------------
export interface Category {
  id: ID;
  name: string;
  image: string | null;
}

export interface Organization {
  id: ID;
  name: string;
  owner_id: ID;             // FK → users.id
  description: string | null;
  email: string | null;
  image: string | null;
}

export interface OrganizationLocation {
  id: ID;
  organization_id: ID;      // FK → organizations.id
  latitude: number;
  longitude: number;
  location_written: string | null;
}

// ------------------------------------
// Spaces & Metadata
// ------------------------------------
export interface Space {
  id: ID;
  name: string;
  description: string | null;
  size: number | null;
  capacity: number | null;
  floor: string | null;
  price_per_hour: number | null; // int
  thumbnail: string | null;
  category_id: ID | null;        // FK → categories.id
  organization_id: ID | null;    // FK → organizations.id
}

export interface SpaceLocation {
  id: ID;
  space_id: ID;                 // FK → spaces.id
  map_location: PgPoint | null;
  location_written: string | null;
}

export interface SpaceImage {
  id: ID;
  space_id: ID;                 // FK → spaces.id
  image: string;                // full-res
  low_res_image: string | null; // optional LQIP
}

export interface Service {
  id: ID;
  name: string;
}

export interface SpaceService {
  id: ID;
  space_id: ID;     // FK → spaces.id
  service_id: ID;   // FK → services.id
  price: number;    // int
}

// ------------------------------------
// Bookings & Reservations
// ------------------------------------
export interface Booking {
  id: ID;
  user_id: ID;               // FK → users.id
  space_id: ID;              // FK → spaces.id
  date: ISODate;
  start_time: ISOTime;
  end_time: ISOTime;
  total_price: number;       // int
  created_at: ISODateTime;
  updated_at: ISODateTime;
}

export interface BookingService {
  id: ID;
  booking_id: ID;           // FK → bookings.id
  space_service_id: ID;     // FK → space_services.id
}

export interface Reservation {
  id: ID;
  user_id: ID;               // FK → users.id
  space_id: ID;              // FK → spaces.id
  status: ReservationStatus;
  total_price: number;       // int
  date: ISODate;
  start_time: ISOTime;
  end_time: ISOTime;
  created_at: ISODateTime;
  updated_at: ISODateTime;
  cancelled_at: ISODateTime | null;
}

export interface ReservationDetail {
  id: ID;
  reservation_id: ID;       // FK → reservations.id
  space_service_id: ID;     // FK → space_services.id
}

// ------------------------------------
// Ratings & Reviews
// ------------------------------------
export interface RatingReview {
  id: ID;
  rating: number;           // int
  review: string | null;    // varchar
  user_id: ID;              // FK → users.id
  space_id: ID;             // FK → spaces.id
  created_at: ISODateTime;
  updated_at: ISODateTime;
}

// ------------------------------------
// Helper Insert/Update types
// - Use these for service/controller input payloads.
// ------------------------------------
type WithTimestamps = {
  created_at?: ISODateTime;
  updated_at?: ISODateTime;
};

export type CreateUser = Omit<User, "id" | "created_at" | "updated_at"> & Partial<WithTimestamps>;
export type UpdateUser = Partial<Omit<User, "id">>;

export type CreateOrganization = Omit<Organization, "id">;
export type UpdateOrganization = Partial<Omit<Organization, "id">>;

export type CreateSpace = Omit<Space, "id">;
export type UpdateSpace = Partial<Omit<Space, "id">>;

export type CreateSpaceService = Omit<SpaceService, "id">;
export type UpdateSpaceService = Partial<Omit<SpaceService, "id">>;

export type CreateBooking = Omit<Booking, "id" | "created_at" | "updated_at"> & Partial<WithTimestamps>;
export type UpdateBooking = Partial<Omit<Booking, "id">>;

export type CreateReservation = Omit<Reservation, "id" | "created_at" | "updated_at" | "cancelled_at"> & Partial<Pick<Reservation, "cancelled_at">> & Partial<WithTimestamps>;
export type UpdateReservation = Partial<Omit<Reservation, "id">>;

export type CreateRatingReview = Omit<RatingReview, "id" | "created_at" | "updated_at"> & Partial<WithTimestamps>;
export type UpdateRatingReview = Partial<Omit<RatingReview, "id">>;

export type CreateOrganizationLocation = Omit<OrganizationLocation, "id">;
export type UpdateOrganizationLocation = Partial<Omit<OrganizationLocation, "id">>;

export type CreateSpaceLocation = Omit<SpaceLocation, "id">;
export type UpdateSpaceLocation = Partial<Omit<SpaceLocation, "id">>;

export type CreateSpaceImage = Omit<SpaceImage, "id">;
export type UpdateSpaceImage = Partial<Omit<SpaceImage, "id">>;

export type CreateCategory = Omit<Category, "id">;
export type UpdateCategory = Partial<Omit<Category, "id">>;

export type CreateService = Omit<Service, "id">;
export type UpdateService = Partial<Omit<Service, "id">>;

export type CreateBookingService = Omit<BookingService, "id">;
export type UpdateBookingService = Partial<Omit<BookingService, "id">>;

export type CreateLocalAuth = LocalAuth; // PK is user_id; supply it
export type UpdateLocalAuth = Partial<LocalAuth>;

export type CreateOAuth = OAuth; // composite key by (user_id, provider) in practice
export type UpdateOAuth = Partial<OAuth>;

export type CreateSession = Omit<Session, "id" | "created_at" | "updated_at"> & Partial<WithTimestamps>;
export type UpdateSession = Partial<Omit<Session, "id">>;

export type CreatePhoneVerificationCode = Omit<PhoneVerificationCode, "id" | "created_at"> & { created_at?: ISODateTime };
export type UpdatePhoneVerificationCode = Partial<Omit<PhoneVerificationCode, "id">>;

export type CreatePasswordReset = Omit<PasswordReset, "id" | "created_at"> & { created_at?: ISODateTime };
export type UpdatePasswordReset = Partial<Omit<PasswordReset, "id">>;

// ------------------------------------
// "relations" read models
// ------------------------------------
export interface OrganizationFull extends Organization {
  owner?: Pick<User, "id" | "full_name" | "email" | "image">;

  locations: OrganizationLocation[];

  spaces: (Space & {
    locations: SpaceLocation[];
    images: SpaceImage[];
    services: (SpaceService & { service?: Service })[];

    // Nested user activity
    bookings: (Booking & {
      user?: Pick<User, "id" | "full_name" | "email">;
      items: (BookingService & {
        space_service?: SpaceService & { service?: Service };
      })[];
    })[];

    reservations: (Reservation & {
      user?: Pick<User, "id" | "full_name" | "email">;
      details: (ReservationDetail & {
        space_service?: SpaceService & { service?: Service };
      })[];
    })[];

    rating_reviews: (RatingReview & {
      user?: Pick<User, "id" | "full_name" | "email" | "image">;
    })[];
  })[];
}