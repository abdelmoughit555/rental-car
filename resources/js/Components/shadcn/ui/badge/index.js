import { cva } from 'class-variance-authority';

export { default as Badge } from './Badge.vue';

export const badgeVariants = cva(
  'inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2',
  {
    variants: {
      variant: {
        default:
          'border-transparent bg-primary text-primary-foreground hover:bg-primary/80',
        secondary:
          'border-transparent bg-secondary text-secondary-foreground hover:bg-secondary/80',
        destructive:
          'border-transparent bg-destructive text-destructive-foreground hover:bg-destructive/80',
        red: 'border-transparent bg-red-600/90 text-red-100 hover:bg-red-800/70',
        gray: 'border-transparent bg-gray-600/90 text-gray-100 hover:bg-gray-700/70',
        pink: 'border-transparent bg-pink-600/90 text-pink-100 hover:bg-pink-700/70',
        blue: 'border-transparent bg-blue-600/90 text-blue-100 hover:bg-blue-700/70',
        green: 'border-transparent bg-green-600/90 text-green-100 hover:bg-green-700/70',
        indigo: 'border-transparent bg-indigo-600/90 text-indigo-100 hover:bg-indigo-700/70',
        purple: 'border-transparent bg-purple-600/90 text-purple-100 hover:bg-purple-700/70',
        orange: 'border-transparent bg-orange-600/90 text-orange-100 hover:bg-orange-700/70',
        yellow: 'border-transparent bg-yellow-600/90 text-yellow-100 hover:bg-yellow-700/70',
        outline: 'text-foreground',
      },
    },
    defaultVariants: {
      variant: 'default',
    },
  },
);
