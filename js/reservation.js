// Handle room selection
  const buttons = document.querySelectorAll(".select-room");
  const summary = document.getElementById("summary-content");
  const roomInput = document.getElementById("room_type");

  buttons.forEach(btn => {
    btn.addEventListener("click", () => {
      let card = btn.closest(".room-card");
      let room = card.getAttribute("data-room");
      let price = card.querySelector("p").innerHTML;

      // Update hidden input
      roomInput.value = room;

      // Update summary
      summary.innerHTML = `<p><strong>${room.toUpperCase()}</strong> selected.<br>${price}</p>`;
    });
  });

  