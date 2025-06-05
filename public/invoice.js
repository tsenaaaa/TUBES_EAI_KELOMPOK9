function formatRupiah(number) {
  return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(number);
}

const params = new URLSearchParams(window.location.search);

document.getElementById('i-guest').innerText = params.get('guest_name') || '-';
document.getElementById('i-room').innerText = params.get('room_number') || '-';
document.getElementById('i-service').innerText = params.get('service_type') || '-';
document.getElementById('i-amount').innerText = formatRupiah(params.get('total_amount') || 0);
document.getElementById('i-method').innerText = params.get('payment_method') || '-';
document.getElementById('i-note').innerText = params.get('notes') || '-';
document.getElementById('i-date').innerText = new Date().toLocaleString('id-ID');
