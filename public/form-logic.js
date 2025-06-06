document.getElementById('paymentForm').addEventListener('submit', async function (e) {
  e.preventDefault();

  const guestName = document.getElementById('guest_name').value;
  const roomNumber = document.getElementById('room_number').value;
  const serviceType = document.getElementById('service_type').value;
  const rawAmount = document.getElementById('amount').value.replace(/\./g, '');
  const amount = parseFloat(rawAmount);
  const method = document.querySelector('input[name="method"]:checked').value;
  const note = document.getElementById('note').value;

  if (!guestName || !roomNumber || !serviceType || isNaN(amount) || !method) {
    alert("Semua data wajib diisi dengan benar.");
    return;
  }

  const query = `
    mutation {
      pay(
        guest_name: "${guestName}",
        room_number: "${roomNumber}",
        service_type: "${serviceType}",
        total_amount: ${amount},
        payment_method: "${method}",
        notes: "${note}"
      )
    }
  `;

  const res = await fetch('index.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({ query })
  });

  const json = await res.json();
  if (json.data?.pay) {
    const params = new URLSearchParams({
      guest_name: guestName,
      room_number: roomNumber,
      service_type: serviceType,
      total_amount: amount,
      payment_method: method,
      notes: note
    });

    window.location.href = `invoice.html?${params.toString()}`;
  } else {
    alert(json.errors?.[0]?.message || 'Gagal menyimpan data.');
  }
});
