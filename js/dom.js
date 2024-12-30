const iconConfig = {
  error: {
    class: 'fa-times-circle',
    color: 'red'
  },
  success: {
    class: 'fa-check-circle',
    color: '#6cbe77'
  },
  warning: {
    class: 'fa-warning',
    color: 'orange'
  }
 };
 
 const dataRules = [
  { id: 1, successRange: [20, 40] },
  { id: 2, successRange: [50, 80] },
  { id: 3, successRange: [50, 80] }
 ];
 
 function updateIcons() {
  dataRules.forEach(rule => {
    const dataElement = document.getElementById(`data${rule.id}`);
    const iconElement = document.getElementById(`icon-status${rule.id}`);
    
    if (!dataElement || !iconElement) return;
 
    const value = parseFloat(dataElement.innerText);
    let status = 'warning';
 
    if (value === 0) {
      status = 'error';
    } else if (value >= rule.successRange[0] && value <= rule.successRange[1]) {
      status = 'success';
    }
 
    iconElement.classList.remove(
      iconConfig.error.class,
      iconConfig.success.class,
      iconConfig.warning.class
    );
 
    iconElement.classList.add(iconConfig[status].class);
    iconElement.style.color = iconConfig[status].color;
  });
 }
 
 let animationFrameId;
 function updateLoop() {
  updateIcons();
  animationFrameId = requestAnimationFrame(updateLoop);
 }
 
 updateLoop();
 
 function cleanup() {
  cancelAnimationFrame(animationFrameId);
 }