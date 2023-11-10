import '../../bootstrap';
// import '../../elements/turbo-echo-stream-tag';
import '../../libs';
import 'flowbite';


if(import.meta.env.VITE_APP_ENV === 'production') {
  document.addEventListener("turbo:frame-missing", (event) => {
    const { detail: { response, visit } } = event;
    event.preventDefault();
    // visit(response.url);
  });
}