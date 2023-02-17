import Axios from 'axios'

function request (method, url, data) {
  const request = {
    method: method,
    url: url
  };

  if (data) {
    request.data = data
  }

  request.headers = {
    'Content-Type': 'application/json'
  };

  return new Promise((resolve, reject) => {
    Axios(request)
      .then(function (response) {
        if (response && response.data) {
          resolve(response.data)
        } else {
          resolve(response)
        }
      })
      .catch(function (error) {
        reject(error.response.data)
      })
  })
}

function makeCall (arg, func) {
  return function () {
    const self = this;
    const args = Array.prototype.slice.call(arguments);
    const appliedArgs = [arg].concat(args);

    return func.apply(self, appliedArgs)
  }
}

export default {
  get: makeCall('get', request),
  post: makeCall('post', request),
  put: makeCall('put', request),
  delete: makeCall('delete', request)
}
