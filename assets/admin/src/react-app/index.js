import { createRoot } from '@wordpress/element';

import App from './components/App';

document.addEventListener('DOMContentLoaded', () => {
    const appEl = document.getElementById('ajax-react-app');
    const root = createRoot(appEl);
    root.render(<App />);
})