const statusConfig = {  
  error: {  
    iconClass: 'fa-times-circle',  
    iconColor: 'rgb(239 68 68)', // text-red-500  
    bgColor: 'rgb(254 226 226)', // bg-red-100  
    iconBgColor: 'rgb(254 202 202)', // bg-red-200  
    text: 'Error'  
  },  
  success: {  
    iconClass: 'fa-check-circle',  
    iconColor: 'rgb(34 197 94)', // text-green-500  
    bgColor: 'rgb(220 252 231)', // bg-green-100  
    iconBgColor: 'rgb(187 247 208)', // bg-green-200  
    text: 'Normal'  
  },  
  warning: {  
    iconClass: 'fa-exclamation-circle',  
    iconColor: 'rgb(234 179 8)', // text-yellow-500  
    bgColor: 'rgb(254 249 195)', // bg-yellow-100  
    iconBgColor: 'rgb(254 240 138)', // bg-yellow-200  
    text: 'Warning'  
  }  
};  
  
const dataRules = [  
  {   
    id: 1,   
    successRange: [20, 40],  
    type: 'temperature',  
    statusTexts: {  
      error: 'Too Low',  
      success: 'Normal',  
      warning: 'Check Temp'  
    }  
  },  
  {   
    id: 2,   
    successRange: [50, 80],  
    type: 'humidity',  
    statusTexts: {  
      error: 'Too Dry',  
      success: 'Good',  
      warning: 'Check Humid'  
    }  
  },  
  {   
    id: 3,   
    successRange: [50, 80],  
    type: 'soil',  
    statusTexts: {  
      error: 'Too Dry',  
      success: 'Good',  
      warning: 'Check Soil'  
    }  
  }  
];  
  
function updateMetrics() {  
  dataRules.forEach(rule => {  
    const dataElement = document.getElementById(`data${rule.id}`);  
    const iconElement = document.getElementById(`icon-status${rule.id}`);  
    const containerElement = iconElement?.closest('.bg-white');  
    const headerIconContainer = containerElement?.querySelector(`[class*="bg-"][class*="-100"]`);  
    const statusTextElement = containerElement?.querySelector('span[class*="text-[10px]"]');  
      
    if (!dataElement || !iconElement || !containerElement || !headerIconContainer || !statusTextElement) return;  
  
    const value = parseFloat(dataElement.innerText);  
    let status = 'warning';  
  
    if (value === 0) {  
      status = 'error';  
    } else if (value >= rule.successRange[0] && value <= rule.successRange[1]) {  
      status = 'success';  
    }  
  
    const config = statusConfig[status];  
  
    // Update icon  
    iconElement.classList.remove(  
      statusConfig.error.iconClass,  
      statusConfig.success.iconClass,  
      statusConfig.warning.iconClass  
    );  
    iconElement.classList.add(config.iconClass);  
      
    // Update status text  
    statusTextElement.textContent = rule.statusTexts[status];  
  
    // Update header icon container color based on status  
    if (status === 'error') {  
      headerIconContainer.className = headerIconContainer.className.replace(/bg-\w+-100/, 'bg-red-100');  
      const headerIcon = headerIconContainer.querySelector('i');  
      if (headerIcon) {  
        headerIcon.className = headerIcon.className.replace(/text-\w+-600/, 'text-red-600');  
      }  
    }  
  });  
}  
  
let animationFrameId;  
function updateLoop() {  
  updateMetrics();  
  animationFrameId = requestAnimationFrame(updateLoop);  
}  
  
// Start the update loop  
updateLoop();  
  
// Cleanup function  
function cleanup() {  
  cancelAnimationFrame(animationFrameId);  
}  
