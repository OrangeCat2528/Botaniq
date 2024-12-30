//  Custom Popup Animation for Botaniq SuperApp
class PopupAnimation {
    constructor(options = {}) {
        this.defaults = {
            modalClass: 'hidden',
            contentClass: 'scale-90 opacity-0',
            animationDuration: 300
        };
        
        this.options = { ...this.defaults, ...options };
    }

    init(modalId, contentId, triggerSelector = null) {
        this.modal = document.getElementById(modalId);
        this.content = document.getElementById(contentId);
        
        if (!this.modal || !this.content) {
            console.error('Modal or content elements not found');
            return;
        }

        // Create backdrop if doesn't exist
        this.backdrop = document.createElement('div');
        this.backdrop.className = 'fixed inset-0 bg-black bg-opacity-50 transition-opacity duration-300 opacity-0';
        this.backdrop.style.pointerEvents = 'none';
        this.modal.insertBefore(this.backdrop, this.modal.firstChild);

        // Set initial state
        this.modal.classList.add(this.options.modalClass);
        this.content.classList.add(...this.options.contentClass.split(' '));

        // Add base modal classes
        const baseClasses = [
            'fixed',
            'inset-0',
            'flex',
            'items-center',
            'justify-center',
            'z-50'
        ];
        
        baseClasses.forEach(className => {
            if (!this.modal.classList.contains(className)) {
                this.modal.classList.add(className);
            }
        });

        if (triggerSelector) {
            this.triggers = document.querySelectorAll(triggerSelector);
            this.bindEvents();
        }
        this.addEventListeners();
    }

    addEventListeners() {
        this.modal.addEventListener('click', (e) => {
            if (e.target === this.modal) {
                this.close();
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !this.modal.classList.contains(this.options.modalClass)) {
                this.close();
            }
        });
    }

    bindEvents() {
        this.triggers?.forEach(trigger => {
            trigger.addEventListener('click', () => this.open());
        });
    }

    open() {
        this.modal.classList.remove(this.options.modalClass);
        
        requestAnimationFrame(() => {
            this.backdrop.style.pointerEvents = 'auto';
            this.backdrop.classList.add('opacity-100');
            
            this.content.classList.remove(...this.options.contentClass.split(' '));
        });
    }

    close() {
        this.backdrop.style.pointerEvents = 'none';
        this.backdrop.classList.remove('opacity-100');
        
        this.content.classList.add(...this.options.contentClass.split(' '));
        
        setTimeout(() => {
            this.modal.classList.add(this.options.modalClass);
        }, this.options.animationDuration);
    }

    addCloseTrigger(selector) {
        if (!selector) return;
        
        const closeTriggers = this.modal.querySelectorAll(selector);
        closeTriggers.forEach(trigger => {
            trigger.addEventListener('click', () => this.close());
        });
    }
}

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = PopupAnimation;
}