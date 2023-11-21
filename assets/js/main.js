jQuery(document).ready(function ($) {
  $("#taxonomy-search-autocomplete").empty().hide();
  var xhr; // Variable to store the AJAX request
  // Autocomplete search
  $("#term_search").on("input", function () {
    var searchQuery = $(this).val();
    var taxonomy = $(this).closest("form").data("taxonomy"); // Get the taxonomy from the closest form data attribute
    var nonce = $("#taxonomy_search_autocomplete_nonce").val();

    // Cancel previous AJAX request if it's still ongoing
    if (xhr && xhr.readyState !== 6) {
      xhr.abort();
    }
    if (searchQuery.length > 0) {
      xhr = $.ajax({
        url: tax_search_ajax.ajax_url,
        type: "POST", // Change method to POST
        data: {
          action: "taxonomy_search_autocomplete",
          nonce: nonce,
          search_query: searchQuery,
          taxonomy: taxonomy, // Pass the taxonomy parameter
        },
        beforeSend: function (xhr) {
          xhr.setRequestHeader("X-WP-Nonce", nonce);
        },
        success: function (response) {
          if (response.success) {
            var results = response.data;
            var autocompleteContainer = $("#taxonomy-search-autocomplete");

            // Clear previous results
            autocompleteContainer.empty();

            console.log(results);

            // Append new results
            $.each(results, function (index, result) {
              var suggestion = $("<div>")
                .addClass("taxonomy-autocomplete-suggestion")
                .text(result.name)
                .on("click", function () {
                  var termName = $(this).text();
                  $("#term_search").val(termName);
                  autocompleteContainer.empty().hide();
                  var termUrl = result.url; // Assuming 'slug' is the property that contains the term slug
                  window.location.href = termUrl;
                });
              autocompleteContainer.append(suggestion);
            });

            // Show autocomplete container
            autocompleteContainer.show();
          } else {
            console.log(response.data);
          }
        },
        error: function (xhr, status, error) {
          console.log(xhr.responseText);
        },
      });
    } else {
      // Clear and hide autocomplete container
      $("#taxonomy-search-autocomplete").empty().hide();
    }
  });

  // Hide autocomplete container on outside click
  $(document).on("click", function (e) {
    var container = $("#taxonomy-search-autocomplete");
    if (!container.is(e.target) && container.has(e.target).length === 0) {
      container.empty().hide();
    }
  });
});
