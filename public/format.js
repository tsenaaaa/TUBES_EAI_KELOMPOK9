document.getElementById('amount').addEventListener('input', function (e) {
  let value = e.target.value.replace(/\D/g, '');
  if (value) {
    e.target.value = parseInt(value).toLocaleString('id-ID');
  } else {
    e.target.value = '';
  }
});
