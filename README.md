# Online Hotel Booking System

## Overview

The Online Hotel Booking System is a comprehensive web-based application designed to facilitate hotel room reservations, user management, and administrative operations. Built with PHP and MySQL, this system provides a seamless experience for guests to book rooms, manage their profiles, and for administrators to oversee bookings and room availability.

## Features

### User Features
- **User Registration and Authentication**: Secure signup and login with password hashing and CSRF protection
- **Room Booking**: Browse available rooms (Standard, Deluxe, Suite) and make reservations
- **User Dashboard**: View booking history, manage personal information, and track reservation status
- **Special Offers**: Access to promotional deals and special packages
- **Meal Ordering**: Order meals during stay
- **Payment Integration**: Secure payment processing with callback handling
- **Password Recovery**: Forgot password functionality with email reset
- **Reviews and Testimonials**: View and submit hotel reviews
- **Gallery**: Browse hotel images and amenities
- **Contact Form**: Get in touch with hotel management

### Admin Features
- **Admin Dashboard**: Comprehensive overview of bookings, users, and rooms
- **Room Management**: Add, update, and manage room inventory
- **Booking Management**: View, confirm, cancel, and manage all reservations
- **User Management**: Administer user accounts and roles
- **Meal Management**: Manage meal options and orders
- **Analytics**: Monitor booking trends and revenue

## Technologies Used

- **Backend**: PHP 7.4+
- **Database**: MySQL
- **Frontend**: HTML5, CSS3, JavaScript
- **Styling**: Custom CSS with responsive design
- **Icons**: Font Awesome
- **Image Slider**: Swiper.js
- **Security**: CSRF tokens, password hashing (bcrypt)

## Prerequisites

- XAMPP (or similar Apache/MySQL/PHP stack)
- Web browser (Chrome, Firefox, Safari, Edge)
- Internet connection for external resources (CDNs)

## Default Admin Credentials

- **Email**: admin@echobay.com
- **Password**: admin123

## Project Structure

```
Online_Hotel_Booking/
├── Html/                    # Main PHP pages
│   ├── index.php           # Homepage
│   ├── Reservation.php     # Booking form
│   ├── user_dashboard.php  # User dashboard
│   ├── admin_dashboard.php # Admin panel
│   └── ...                 # Other pages
├── CSS/                    # Stylesheets
│   ├── Style.css          # Main stylesheet
│   ├── auth.css           # Authentication styles
│   └── ...                # Page-specific styles
├── js/                     # JavaScript files
│   ├── auth.js            # Authentication scripts
│   ├── homeslide.js       # Homepage slider
│   └── ...                # Other scripts
├── images/                 # Images and media
│   ├── Logo/              # Logo files
│   ├── icons/             # Icon assets
│   └── ...                # Hotel images
├── config.php             # Database configuration
├── init_database.php      # Database initialization
├── login.php              # User login
├── signup.php             # User registration
├── admin_login.php        # Admin login
└── README.md              # This file
```

## Usage

### For Guests
1. **Register**: Create an account using the signup form
2. **Login**: Access your account with email and password
3. **Browse**: Explore rooms, special offers, and amenities
4. **Book**: Select dates, choose room type, and complete booking
5. **Manage**: View bookings and update profile in dashboard

### For Administrators
1. **Login**: Use admin credentials to access admin panel
2. **Manage Rooms**: Add/update room inventory and pricing
3. **Monitor Bookings**: View all reservations and update status
4. **User Management**: Oversee user accounts
5. **Analytics**: Review booking statistics

## Security Features

- CSRF protection on forms
- Password hashing using bcrypt
- Input sanitization and validation
- Session management
- Secure database queries with prepared statements

## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Support

For support, email support@echobaylodge.com or use the contact form on the website.

## Acknowledgments

- Font Awesome for icons
- Swiper.js for image sliders
- PHP community for documentation and best practices
