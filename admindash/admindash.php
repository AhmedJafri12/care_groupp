<?php
require_once __DIR__ . '/auth.php';
require_login();

// Load the static HTML dashboard and inject session-driven details (name, role, logout link)
$html = file_get_contents(__DIR__ . '/admindash.html');
if ($html === false) {
    http_response_code(500);
    echo 'Dashboard file not found.';
    exit;
}

$name = $_SESSION['name'] ?? $_SESSION['username'] ?? 'User';

// Inject a small script right before </body> to update UI with session values without rewriting the whole HTML
$inject = "\n<script>\n(function(){\n  try {\n    // Set displayed user name in navbar\n    var dd = document.getElementById('dropdownUser');\n    if (dd) {\n      var span = dd.querySelector('span');\n      if (span) span.textContent = " . json_encode($name) . ";\n    }\n    // Fix Logout link to point to logout.php\n    var logout = Array.from(document.querySelectorAll('a.nav-link, a.dropdown-item')).find(function(a){\n      if (!a || !a.textContent) return false;\n      var t = a.textContent.trim().toLowerCase();\n      return t === 'sign out' || t === 'logout';\n    });\n     if (logout) logout.setAttribute('href','logout.php');\n 
    // Route the Doctors > Add button to the registration form
    var addDoctorBtn = document.querySelector('#doctors .btn.btn-primary[data-bs-target="#addDoctorModal"]');\n     if (addDoctorBtn) {\n       addDoctorBtn.removeAttribute('data-bs-toggle');\n       addDoctorBtn.removeAttribute('data-bs-target');\n       addDoctorBtn.addEventListener('click', function(ev){ ev.preventDefault(); window.location.href = 'docregform/form.php'; });\n     }\n     // Remove/hide the old AddDoctor modal if present
    var oldModal = document.getElementById('addDoctorModal');
    if (oldModal) { oldModal.parentNode && oldModal.parentNode.removeChild(oldModal); }

    // Add 'View All Patients' button in Patients header
    var patientsHeader = document.querySelector('#patients .d-flex.justify-content-between.align-items-center.mb-4');
    if (patientsHeader && !patientsHeader.querySelector('a[href="viewpatients.php"]')) {
      var patientsBtn = document.createElement('a');
      patientsBtn.className = 'btn btn-outline-secondary';
      patientsBtn.href = 'viewpatients.php';
      patientsBtn.innerHTML = '<i class="fas fa-list me-2"></i>View All Patients';
      var rightBox = patientsHeader.querySelector('.d-flex.gap-2');
      if (!rightBox) {
        rightBox = document.createElement('div');
        rightBox.className = 'd-flex gap-2';
        var addBtn = patientsHeader.querySelector('.btn.btn-primary');
        if (addBtn && addBtn.parentElement === patientsHeader) {
          patientsHeader.replaceChild(rightBox, addBtn);
          rightBox.appendChild(addBtn);
        } else {
          patientsHeader.appendChild(rightBox);
        }
      }
      rightBox.appendChild(patientsBtn);
    }

    // Add 'View All Cities' button in Cities header
    var citiesHeader = document.querySelector('#cities .d-flex.justify-content-between.align-items-center.mb-4');
    if (citiesHeader && !citiesHeader.querySelector('a[href="viewcities.php"]')) {
      var citiesBtn = document.createElement('a');
      citiesBtn.className = 'btn btn-outline-secondary';
      citiesBtn.href = 'viewcities.php';
      citiesBtn.innerHTML = '<i class="fas fa-list me-2"></i>View All Cities';
      var rightBox2 = citiesHeader.querySelector('.d-flex.gap-2');
      if (!rightBox2) {
        rightBox2 = document.createElement('div');
        rightBox2.className = 'd-flex gap-2';
        var addBtn2 = citiesHeader.querySelector('.btn.btn-primary');
        if (addBtn2 && addBtn2.parentElement === citiesHeader) {
          citiesHeader.replaceChild(rightBox2, addBtn2);
          rightBox2.appendChild(addBtn2);
        } else {
          citiesHeader.appendChild(rightBox2);
        }
      }
      rightBox2.appendChild(citiesBtn);
    }
\n   } catch(e) { console.error(e); }\n})();\n</script>\n";

// Place injection before </body>
$pos = strripos($html, '</body>');
if ($pos !== false) {
    $html = substr($html, 0, $pos) . $inject . substr($html, $pos);
} else {
    // Fallback: append at the end
    $html .= $inject;
}

echo $html;
