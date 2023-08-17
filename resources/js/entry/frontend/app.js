import '../../bootstrap';
import '../../elements/turbo-echo-stream-tag';
import '../../libs';
import 'flowbite';


if(import.meta.env.VITE_APP_ENV === 'production') {
  document.addEventListener("turbo:frame-missing", (event) => {
    const { detail: { response, visit } } = event;
    event.preventDefault();
    // visit(response.url);
  });
}

// const handleResponse = (response) => {
//   console.log(response)
//   const token = response.credential;
//   const decodedToken = jwtDecode(token);
//   const { sub: id, email, name, picture: photoURL } = decodedToken;
//   console.log(decodedToken)
// };

// try {
//   window.google.accounts.id.initialize({
//     client_id: import.meta.env.VITE_GOOGLE_CLIENT_ID,
//     callback: handleResponse,
//   });
// } catch (error) {
//   console.log(error)
// }