import { DirectiveBinding } from 'vue';

type RippleOptions = {
    color?: string;
    opacity?: string;
};

export default {
    mounted(el: HTMLElement, binding: DirectiveBinding<RippleOptions>) {
        el.style.position = 'relative';
        el.style.overflow = 'hidden';

        el.addEventListener('click', function (e: MouseEvent) {
            const ripple = document.createElement('span');
            const rect = el.getBoundingClientRect();

            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;

            ripple.style.width = ripple.style.height = `${size}px`;
            ripple.style.position = 'absolute';
            ripple.style.borderRadius = '50%';
            ripple.style.transform = 'scale(0)';
            ripple.style.opacity = '1';
            ripple.style.pointerEvents = 'none';
            ripple.style.left = `${x}px`;
            ripple.style.top = `${y}px`;

            const { color = 'bg-foreground', opacity = 'bg-opacity-25' } =
                binding.value || {};

            ripple.classList.add('animate-ripple', color, opacity);

            el.appendChild(ripple);

            ripple.addEventListener('animationend', () => {
                ripple.remove();
            });
        });
    },
};
