declare module 'tailwindcss' {
  export interface TailwindConfig {
    content: string[];
    theme: {
      extend: Record<string, unknown>;
    };
    plugins: unknown[];
  }
}

declare module '*.css' {
  const content: { [className: string]: string };
  export default content;
} 