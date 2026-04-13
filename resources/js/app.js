import Quill from 'quill';
import 'quill/dist/quill.snow.css';

const Font = Quill.import('formats/font');
const Table = Quill.import('modules/table');

Font.whitelist = ['sans', 'serif', 'mono', 'display'];
Quill.register(Font, true);

if (typeof Table.register === 'function') {
    Table.register();
}
Quill.register('modules/table', Table, true);

window.Quill = Quill;
