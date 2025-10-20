    const clickButton = document.getElementById('click_button');

    if (clickButton != null) {
    clickButton.addEventListener('click', () => {

        if (document.querySelector('#show_hide').style.display = 'none') {
        document.querySelector('#show_hide').setAttribute('style', 'display: block')
      }
      else {
        document.querySelector('#show_hide').setAttribute('style', 'display: none')
      }
})
};

