function map(parent , data, keys=[]) {

    console.log('inside map function');
    
    keys = keys.length==0? Object.keys(data[0]) : keys;
    var selectElement = document.getElementById(parent);
    var template = selectElement.innerHTML;
    selectElement.innerHTML = '';

    // Loop through the options array and create option elements
    for (var i = 0; i < data.length; i++) {
      var optionHtml = template;

      // Replace placeholders with actual values
      keys.forEach(key =>
        optionHtml = optionHtml.replace('{{d.' + key + '}}', data[i][key])
      );

      selectElement.innerHTML += optionHtml;
    }
}