document.addEventListener("DOMContentLoaded", function () {
  
  // Fetch data from the API
  fetch("http://127.0.0.1:8000/product/all")
    .then(response => response.json())
    .then(data => {
      const products = data; // Assuming your API returns an array of products
      console.log(products);

      const productRow = document.getElementById("productRow");

      products.forEach(product => {
        const colDiv = document.createElement("div");
        colDiv.className = "col-sm-6 col-md-4 col-lg-3";

        const boxDiv = document.createElement("div");
        boxDiv.className = "box";

        const productLink = document.createElement("a");
        productLink.href = product.link; // Assuming each product object has a link property

        const imgBoxDiv = document.createElement("div");
        imgBoxDiv.className = "img-box";

        const img = document.createElement("img");
        img.src = product.image; // Assuming each product object has an image property
        img.alt = product.name; // Assuming each product object has a name property

        imgBoxDiv.appendChild(img);
        productLink.appendChild(imgBoxDiv);

        const detailBoxDiv = document.createElement("div");
        detailBoxDiv.className = "detail-box";

        const productName = document.createElement("h6");
        productName.textContent = product.name; // Assuming each product object has a name property

        const productPrice = document.createElement("h6");
        productPrice.innerHTML = `Price <span>$${product.price}</span>`; // Assuming each product object has a price property

        const productLikes = document.createElement("h6");
        productLikes.textContent = `Likes: ${product.likes}`; // Assuming each product object has a likes property

        detailBoxDiv.appendChild(productName);
        detailBoxDiv.appendChild(productPrice);
        detailBoxDiv.appendChild(productLikes);
        productLink.appendChild(detailBoxDiv);

        const likeButton = document.createElement("button");
        likeButton.textContent = "Like";
        likeButton.className = "like-button";
        likeButton.addEventListener("click", function () {
          // Send a POST request to the API with the product ID
          fetch(`http://127.0.0.1:8000/product/${product.id}/likes`, {
            method: "POST",
            body: JSON.stringify({ productId: product.id }), // Assuming each product has an id property
            headers: {
              "Content-Type": "application/json"
            }
          })  
            .then(response => {
              if (response.ok) {
                // Refresh the page upon successful like
                location.reload();
              } else {
                console.error("Failed to like the product");
              }
            })
            .catch(error => {
              console.error("Error liking the product:", error);
            });
        });

        boxDiv.appendChild(productLink);
        boxDiv.appendChild(likeButton);
        colDiv.appendChild(boxDiv);
        productRow.appendChild(colDiv);
      });
    })
    .catch(error => {
      console.error("Error fetching data:", error);
    });

  // Set href for View All Products button
  document.getElementById("viewAllProducts").href = "your_all_products_page_url";
});


// public/js/product.js
document.addEventListener("DOMContentLoaded", function() {
  const sortButton = document.getElementById('sort-by-likes');
  sortButton.addEventListener('click', function() {
      fetch("http://127.0.0.1:8000/products/sort")
          .then(response => response.json())
          .then(products => {
              updateProductList(products);
          })
          .catch(error => {
              console.error("Error fetching sorted products:", error);
          });
  });
});

function updateProductList(products) {
  console.log(products[0])
  const productRow = document.getElementById('productRow');
  productRow.innerHTML = ''; // Clear previous content
  
  products.forEach(product => {
      const colDiv = document.createElement("div");
      colDiv.className = "col-sm-6 col-md-4 col-lg-3";

      const boxDiv = document.createElement("div");
      boxDiv.className = "box";

      const productLink = document.createElement("a");
      productLink.href = product.link;

      const imgBoxDiv = document.createElement("div");
      imgBoxDiv.className = "img-box";

      const img = document.createElement("img");
      img.src = product.image;
      img.alt = product.name;

      imgBoxDiv.appendChild(img);
      productLink.appendChild(imgBoxDiv);

      const detailBoxDiv = document.createElement("div");
      detailBoxDiv.className = "detail-box";

      const productName = document.createElement("h6");
      productName.textContent = product.name;

      const productPrice = document.createElement("h6");
      productPrice.innerHTML = `Price <span>$${product.price}</span>`;

      const productLikes = document.createElement("h6");
      productLikes.textContent = `Likes: ${product.likes}`;

      detailBoxDiv.appendChild(productName);
      detailBoxDiv.appendChild(productPrice);
      detailBoxDiv.appendChild(productLikes);
      productLink.appendChild(detailBoxDiv);

      const likeButton = document.createElement("button");
      likeButton.textContent = "Like";
      likeButton.className = "like-button";
      likeButton.addEventListener("click", function () {
          fetch(`http://127.0.0.1:8000/product/${product.id}/likes`, {
              method: "POST",
              body: JSON.stringify({ productId: product.id }),
              headers: {
                  "Content-Type": "application/json"
              }
          })  
              .then(response => {
                  if (response.ok) {
                      location.reload();
                  } else {
                      console.error("Failed to like the product");
                  }
              })
              .catch(error => {
                  console.error("Error liking the product:", error);
              });
      });

      boxDiv.appendChild(productLink);
      boxDiv.appendChild(likeButton);
      colDiv.appendChild(boxDiv);
      productRow.appendChild(colDiv);
  });
}

