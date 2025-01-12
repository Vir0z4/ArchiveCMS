document.addEventListener('DOMContentLoaded', function () {
  const tabButtons = document.querySelectorAll('.tab-button');
  const tabContents = document.querySelectorAll('.tab-content');
  let landingTab = document.getElementById('tab-landing');

  tabButtons.forEach(button => {
    button.addEventListener('click', () => {
      const tab = button.getAttribute('data-tab');

      tabContents.forEach(content => {
        content.classList.remove('active');
      });

      tabButtons.forEach(btn => {
        btn.classList.remove('active');
      });

      document.getElementById(tab).classList.add('active');
      button.classList.add('active');

      if (landingTab) {
        landingTab.classList.remove('active');
        landingTab = null;
      }
    });
  });
});