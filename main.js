/* Moralis init code */
const serverUrl = "https://rwg7cessrtfy.usemoralis.com:2053/server";
const appId = "hZTd8XIUMAnoN3h8KyMoSsgKKo2CJ94egLO2EfDD";
Moralis.start({ serverUrl, appId });

/* Authentication code */

async function loginWithMetamask() {
  let submit = document.getElementById("metamask-login");
  user = await Moralis.authenticate({
    signingMessage: "Log in using Moralis",
  })
    .then(function (user) {
      $.ajax({
        url: "call.php", // form action url
        type: "POST", // form submit method get/post
        dataType: "html", // request type html/json/xml
        data: {
          metamask: user.get("ethAddress"),
        }, // serialize form data
        beforeSend: function () {
          // alert.fadeOut();
          submit.innerText = "creating...."; // change submit button text
        },
        success: function (data) {
          // alert.html(data).fadeIn(); // fade in response data
          submit.innerText = "connected"; // reset form
          swal({
            title: "success",
            text: data,
            icon: "success",
            button: "OK",
          });
        },
        error: function (e) {
          swal({
            title: "Error",
            text: "An error occurred",
            icon: "error",
            button: "OK",
          });
        },
      });
    })
    .catch((error) => {
      swal({
        title: "Error",
        text: error.toString(),
        icon: "error",
        button: "OK",
      });
    });
}

async function loginWithPhantomWallet() {
  let submit = document.getElementById("login-phantom");
  user = await Moralis.authenticate({ type: "sol" })
    .then(function (user) {
      const phantom_wallet = localStorage.getItem(`Parse/${appId}/currentUser`);
      let wallet;
      if (phantom_wallet !== null) {
        wallet = JSON.parse(phantom_wallet).solAccounts[0];
        $.ajax({
          url: "call.php", // form action url
          type: "POST", // form submit method get/post
          dataType: "html", // request type html/json/xml
          data: {
            phantom: wallet,
          }, // serialize form data
          beforeSend: function () {
            // alert.fadeOut();
            submit.innerText = "creating...."; // change submit button text
          },
          success: function (data) {
            // alert.html(data).fadeIn(); // fade in response data
            submit.innerText = "connected"; // reset form
            swal({
              title: "success",
              text: data,
              icon: "success",
              button: "OK",
            });
          },
          error: function (e) {
            swal({
              title: "Error",
              text: e.toString(),
              icon: "error",
              button: "OK",
            });
          },
        });
      }
    })
    .catch((error) => {
      swal({
        title: "Error",
        text: error.toString(),
        icon: "error",
        button: "OK",
      });
    });
}

async function logOut() {
  await Moralis.User.logOut();
  console.log("logged out");
}

async function deleteAction(data) {
  $.ajax({
    url: "call.php", // form action url
    type: "POST", // form submit method get/post
    dataType: "html", // request type html/json/xml
    data: data, // serialize form data
    success: function (data) {
      // alert.html(data).fadeIn(); // fade in response data

      swal({
        title: data == "error" ? "error" : "success",
        text: data == "error" ? "login to a wallet." : data,
        icon: data == "error" ? "error" : "success",
        button: "OK",
      });
      window.location.reload();
    },
    error: function (e) {
      swal({
        title: "Error",
        text: e.toString(),
        icon: "error",
        button: "OK",
      });
    },
  });
}
async function createEmail(e) {
  e.preventDefault();
  let email = document.getElementById("exampleInputEmail1").value;
  let submit = document.getElementById("submitemail");
  if (email !== "") {
    $.ajax({
      url: "call.php", // form action url
      type: "POST", // form submit method get/post
      dataType: "html", // request type html/json/xml
      data: {
        email: email,
      }, // serialize form data
      beforeSend: function () {
        // alert.fadeOut();
        submit.innerText = "creating...."; // change submit button text
      },
      success: function (data) {
        // alert.html(data).fadeIn(); // fade in response data

        swal({
          title: data == "error" ? "error" : "success",
          text: data == "error" ? "login to a wallet." : data,
          icon: data == "error" ? "error" : "success",
          button: "OK",
        });
      },
      error: function (e) {
        swal({
          title: "Error",
          text: e.toString(),
          icon: "error",
          button: "OK",
        });
      },
    });
  } else {
    swal({
      title: "Error",
      text: "email is empty",
      icon: "error",
      button: "OK",
    });
  }
}

document.getElementById("submitemail").onclick = createEmail;

// $(document).ready(function() {
//     var form = $('#myForm'); // contact form
//     var submit = $('.login');
//     var registerBtn=$('.register-btn'); // submit button
//     var alert = $('.alert-msg'); // alert div for show alert message

//     // form submit event
//     form.on('submit', function(e) {
//         e.preventDefault(); // prevent default form submit

//         $.ajax({
//             url: 'log.php', // form action url
//             type: 'POST', // form submit method get/post
//             dataType: 'html', // request type html/json/xml
//             data: form.serialize(), // serialize form data
//             beforeSend: function() {
//                 alert.fadeOut();
//                 submit.html('creating....'); // change submit button text
//             },
//             success: function(data) {
//                 alert.html(data).fadeIn(); // fade in response data
//                 form.trigger('reset'); // reset form
//             },
//             error: function(e) {
//                 console.log(e)
//             }
//         });

//     });
// });

// NFTS

async function getNftMetadata(account) {
  const options = {
    address: account,
    chain: "eth",
  };
  const nfts = await Moralis.Web3.getNFTs(options);
  nfts.map((nft) => {
    let uri = fixURL(nft.token_uri);

    fetch(uri)
      .then((response) => response.json())
      .then((data) => {
        $(".nft-box").html(
          $(".nft-box").html() +
            `<div class="card p-2" style="background-color: #000; width: 23%; border-radius: 20px; height: 400px; border: 2px solid rgba(255,255,255,0.4);"><img class="card-img-top" style="border-radius:10px;" src="${fixURL(
              data.image
            )}" alt="Card image cap"><div class="card-body"><h5 class="card-title">${data.name.slice(
              0,
              15
            )}</h5><p class="card-text">${data.description}</p></div></div>`
        );
      });
  });
}

function fixURL(url) {
  if (url.startsWith("ipfs")) {
    return (
      "https://ipfs.moralis.io:2053/ipfs/" + url.split("ipfs://ipfs/").slice(-1)
    );
  } else {
    return url + "?format=json";
  }
}

window.addEventListener("load", async () => {
  await $.ajax({
    url: "call.php", // form action url
    type: "POST", // form submit method get/post
    dataType: "html", // request type html/json/xml
    data: {
      accountDataMetamask: "account",
    }, // serialize form data
    beforeSend: function () {
      // alert.fadeOut();
      $(".the-spinner").html(
        '<div class="spinner-grow" role="status"><span class="sr-only"></span></div>'
      ); // change submit button text
    },
    success: function (data) {
      // alert.html(data).fadeIn(); // fade in response data
      if (data !== "") {
        getNftMetadata(data);
      }
      // swal({
      //   title:"success",
      //   text:data,
      //   icon: "success",
      //   button: "OK",
      // });
    },
    error: function (e) {
      swal({
        title: "Error",
        text: e.toString(),
        icon: "error",
        button: "OK",
      });
    },
  });

  await $.ajax({
    url: "call.php", // form action url
    type: "POST", // form submit method get/post
    dataType: "html", // request type html/json/xml
    data: {
      accountDataPhantom: "account",
    }, // serialize form data
    // beforeSend: function () {
    //   // alert.fadeOut();
    //   $('.the-spinner').html('<div class="spinner-grow" role="status"><span class="sr-only"></span></div>') // change submit button text
    // },
    success: function (data) {
      // alert.html(data).fadeIn(); // fade in response data
      if (data !== "") {
        getNftMetadata(data);
      }
      // swal({
      //   title:"success",
      //   text:data,
      //   icon: "success",
      //   button: "OK",
      // });
    },
    error: function (e) {
      swal({
        title: "Error",
        text: e.toString(),
        icon: "error",
        button: "OK",
      });
    },
  });
});

document.getElementById("metamask-login").onclick = loginWithMetamask;
document.getElementById("login-phantom").onclick = loginWithPhantomWallet;
// document.getElementById("btn-logout").onclick = logOut;
