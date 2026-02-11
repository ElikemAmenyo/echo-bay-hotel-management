// Retrieve Booking Details from Local Storage
const bookingDetails = JSON.parse(localStorage.getItem('bookingDetails'));

// Display Booking Details
if (bookingDetails) {
  const bookingDetailsDiv = document.getElementById('booking-details');
  bookingDetailsDiv.innerHTML = `
    <p><strong>Room Type:</strong> ${bookingDetails.roomType}</p>
    <p><strong>Guest Name:</strong> ${bookingDetails.name}</p>
    <p><strong>Email:</strong> ${bookingDetails.email}</p>
    <p><strong>Phone:</strong> ${bookingDetails.phone}</p>
    <p><strong>Check-in Date:</strong> ${bookingDetails.checkin}</p>
    <p><strong>Check-out Date:</strong> ${bookingDetails.checkout}</p>
  `;
} else {
  document.getElementById('booking-details').innerHTML = `<p>No booking details found.</p>`;
}

// Redirect to Home Page
function goToHomePage() {
  window.location.href = 'index.php';
}