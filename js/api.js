const apiUrl = 'http://localhost:8000/api/';

async function info() {
  // todo implement proper json-rpc flow
  const response = await fetch(apiUrl);
  const decoded = await response.json();
  return decoded.result;
}

async function login() {
  // todo
}

async function order() {
  // todo
}

export {
  info,
  login,
  order,
};
