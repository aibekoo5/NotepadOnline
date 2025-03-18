document.addEventListener('DOMContentLoaded', function() {
  console.log('Task Management System loaded');
});

function autoSave(notepadId) {
  console.log('Auto-saving notepad:', notepadId);
}

function toggleLineThrough(checkbox) {
  const taskId = checkbox.id;
  const isChecked = checkbox.checked;

  const titleSpan = checkbox.nextElementSibling;
  if (isChecked) {
      titleSpan.classList.add('checked');
  } else {
      titleSpan.classList.remove('checked');
  }

  localStorage.setItem(taskId, isChecked);
}

document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
      const taskId = checkbox.id;
      const isChecked = localStorage.getItem(taskId) === 'true';
      checkbox.checked = isChecked;
      toggleLineThrough(checkbox);
  });
});

