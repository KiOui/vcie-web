// Implement rot13 for email obscurification
javascript:String.prototype.rot13 = function(s)
{
  return (s = (s) ? s : this).split('').map(function(_)
  {
    if (!_.match(/[A-Za-z]/)) return _;
    c = _.charCodeAt(0)>=96;
    k = (_.toLowerCase().charCodeAt(0) - 96 + 12) % 26 + 1;
    return String.fromCharCode(k + (c ? 96 : 64));
  }
  ).join('');
};

/* vcieAddToProducts function will add n products to each input field of a
 * category scaled by the values in `scale`
 * @param {string[]} input_ids - The IDs the input fields to be affected
 * @param {Object} scales - A dictionary containing the scale values for each input field by id
 * @param {number} [n=1] - The unscaled amount of products to be added
 */
function vcieAddToProducts(input_ids, scales, n) {
  n = typeof n !== 'undefined' ? n : 1;

  $.each(input_ids, function _vcieAddToProductsEach(index, input_id) {
    var HIDDEN_VALUE_DATA_NAME = "vcieRealValue";
    var field = $(document.getElementById(input_id));
    var hidden_value = field.data(HIDDEN_VALUE_DATA_NAME);
    hidden_value = typeof hidden_value !== 'undefined' ? hidden_value : parseFloat(field.val());

    if (isNaN(hidden_value)) {
      hidden_value = 0.0;
    }
    else if (Math.round(hidden_value) !== parseInt(field.val())) {
      hidden_value = parseFloat(field.val());
    }

    hidden_value += n * scales[input_id];
    hidden_value = Math.max(hidden_value, 0);
    field.data(HIDDEN_VALUE_DATA_NAME, hidden_value);
    field.val(Math.round(hidden_value));
  });
}