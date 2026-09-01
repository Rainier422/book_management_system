const baseApiUrl = "http://localhost/Japson";
document.addEventListener("DOMContentLoaded", () => {
    displayCategories();
    displayBooks();
    document
        .getElementById("btn-submit")
        .addEventListener("click", () => {
            insertBook();
        });
});
const displayBooks = async () => {
    try {
        const response = await axios.get(
            `${baseApiUrl}/books.php`,
            {
                params: {
                    operation: "getAllBooks"
                }
            }
        );
        console.log("Books Response:", response.data);
        if (response.status === 200) {
            if (Array.isArray(response.data)) {
                displayBooksTable(response.data);
            } else {
                console.error(
                    "Books API Error:",
                    response.data
                );
                alert(
                    response.data.message ||
                    "Error loading books!"
                );
            }
        }
    } catch (error) {
        console.error("Books Error:", error);
        if (error.response) {
            console.error(
                "Server Response:",
                error.response.data
            );
        }
        alert("Error loading books!");
    }
};
const displayBooksTable = (books) => {
    const tableDiv =
        document.getElementById("table-div");
    tableDiv.innerHTML = "";
    const table =
        document.createElement("table");
    table.setAttribute("border", "1");
    table.style.borderCollapse = "collapse";
    const thead =
        document.createElement("thead");
    thead.innerHTML = `
        <tr>
            <th style="border: 1px solid black; padding: 6px;">TITLE</th>
            <th style="border: 1px solid black; padding: 6px;">AUTHOR</th>
            <th style="border: 1px solid black; padding: 6px;">ISBN</th>
            <th style="border: 1px solid black; padding: 6px;">CATEGORY</th>
            <th style="border: 1px solid black; padding: 6px;">YEAR PUBLISHED</th>
            <th style="border: 1px solid black; padding: 6px;">PUBLISHER</th>
        </tr>
    `;
    table.appendChild(thead);
    const tbody =
        document.createElement("tbody");
    books.forEach(book => {
        let row =
            document.createElement("tr");
        row.innerHTML = `
            <td style="border: 1px solid black; padding: 6px;">${book.book_title}</td>
            <td style="border: 1px solid black; padding: 6px;">${book.book_author}</td>
            <td style="border: 1px solid black; padding: 6px;">${book.book_isbn}</td>
            <td style="border: 1px solid black; padding: 6px;">${book.cat_name}</td>
            <td style="border: 1px solid black; padding: 6px;">${book.book_year_published}</td>
            <td style="border: 1px solid black; padding: 6px;">${book.book_publisher}</td>
        `;
        tbody.appendChild(row);
    });
    table.appendChild(tbody);
    tableDiv.appendChild(table);
};
const displayCategories = async () => {
    try {
        const select =
            document.getElementById("book-category");
        const response = await axios.get(
            `${baseApiUrl}/categories.php`,
            {
                params: {
                    operation: "getCategories"
                }
            }
        );
        console.log("Categories Response:", response.data);
        if (response.status === 200) {
            if (Array.isArray(response.data)) {
                select.innerHTML = "";
                response.data.forEach(category => {
                    let option =
                        document.createElement("option");
                    option.innerText =
                        category.cat_name;
                    option.value =
                        category.cat_id;
                    select.appendChild(option);
                });
            } else {
                console.error(
                    "Categories API Error:",
                    response.data
                );
                alert(
                    response.data.message ||
                    "Error loading categories!"
                );
            }
        }
    } catch (error) {
        console.error("Categories Error:", error);
        if (error.response) {
            console.error(
                "Server Response:",
                error.response.data
            );
        }
        alert("Error loading categories!");
    }
};
const insertBook = async () => {
    const jsonData = {
        title:
            document.getElementById("book-title").value.trim(),
        author:
            document.getElementById("book-author").value.trim(),
        isbn:
            document.getElementById("book-isbn").value.trim(),
        categoryId:
            document.getElementById("book-category").value,
        year:
            document.getElementById("book-year").value.trim(),
        publisher:
            document.getElementById("book-publisher").value.trim()
    };

    if (
        jsonData.title === "" ||
        jsonData.author === "" ||
        jsonData.isbn === "" ||
        jsonData.categoryId === "" ||
        jsonData.year === "" ||
        jsonData.publisher === ""
    ) {
        alert("Please fill out all fields.");
        return;
    }
    const formData =
        new FormData();
    formData.append(
        "operation",
        "insertBook"
    );
    formData.append(
        "json",
        JSON.stringify(jsonData)
    );
    try {
        const response = await axios({
            url:
                `${baseApiUrl}/books.php`,
            method:
                "POST",
            data:
                formData
        });
        console.log(
            "Insert Response:",
            response.data
        );
        if (response.data && response.data.error === false) {
            alert(
                response.data.message ||
                "Book successfully added!"
            );
            clearForm();
            displayBooks();
        } else {
            alert(
                (response.data && response.data.message) ||
                "ERROR"
            );
        }
    } catch (error) {
        console.error(
            "Insert Error:",
            error
        );
        if (error.response) {
            console.error(
                "Server Response:",
                error.response.data
            );
        }
        alert("ERROR");
    }

};
const clearForm = () => {
    document.getElementById("book-title").value = "";
    document.getElementById("book-author").value = "";
    document.getElementById("book-isbn").value = "";
    document.getElementById("book-year").value = "";
    document.getElementById("book-publisher").value = "";

};