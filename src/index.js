import './index.scss';
import { render } from '@wordpress/element'; // WordPress React wrapper
import Anther from './react/Anther';
import App from './react/App'
render(<App />, document.getElementById('root'));
render(<Anther />, document.getElementById('rot'));