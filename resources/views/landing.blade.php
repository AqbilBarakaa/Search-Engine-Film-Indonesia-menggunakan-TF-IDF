<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Indonesian Movies</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Lato:wght@400;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-main: #222831;
            --bg-content: #393E46;
            --text-primary: #EEEEEE;
            --text-secondary: #929AAB;
            --accent-color: #00ADB5;
            --border-color: #4A5058;
            --hover-color: #4A5058;
        }
        body {
            background-color: var(--bg-main);
            color: var(--text-primary);
            font-family: 'Lato', sans-serif;
        }
        .jumbotron-custom h1 {
            font-family: 'Montserrat', sans-serif;
            color: var(--text-primary);
        }
        .jumbotron-custom p {
            color: var(--text-secondary);
        }
        .content-wrapper {
            background-color: var(--bg-content);
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            border-radius: 0.5rem;
        }
        #searchInput, .form-select, .form-control {
            background-color: var(--bg-main);
            border-color: var(--border-color);
            color: var(--text-primary);
        }
        #searchInput::placeholder, .form-control::placeholder {
            color: var(--text-secondary);
        }
        #searchInput:focus, .form-select:focus, .form-control:focus {
            background-color: var(--bg-main);
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.25rem rgba(0, 173, 181, 0.25);
            color: var(--text-primary);
        }
        .table {
            color: var(--text-primary);
        }
        #moviesTable thead th {
            color: var(--accent-color);
            border-bottom: 2px solid var(--border-color);
            cursor: pointer;
        }
        #moviesTable tbody tr {
            border-color: var(--border-color);
        }
        #moviesTable tbody tr:hover {
            background-color: var(--hover-color);
        }
        .fw-bold.text-dark {
            color: var(--text-primary) !important;
        }
        .badge.bg-success {
            background-color: var(--accent-color) !important;
            color: var(--bg-main);
            font-weight: bold;
        }
        .modal-content {
            background-color: var(--bg-content);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            border-radius: 0.5rem;
        }
        .modal-header {
            border-bottom: 1px solid var(--border-color);
        }
        .modal-header .modal-title {
            color: var(--accent-color);
            font-family: 'Montserrat', sans-serif;
        }
        .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%);
        }
        .modal-body .list-group-item {
            background-color: transparent;
            border-color: var(--border-color);
            color: var(--text-primary);
        }
        .modal-footer .btn-secondary {
            background-color: var(--border-color);
            border-color: var(--border-color);
        }
        .dataTables_length, .dataTables_filter, .dataTables_info, .dataTables_paginate {
            color: var(--text-secondary) !important;
        }
        .page-link {
            background-color: var(--bg-content);
            border-color: var(--border-color);
            color: var(--text-primary);
        }
        .page-item.active .page-link {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
            color: var(--bg-main);
        }
        .page-link:hover {
            background-color: var(--hover-color);
            color: var(--text-primary);
        }
        .form-label {
            color: var(--text-secondary);
        }
        .btn-primary {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
            color: var(--bg-main) !important;
            font-weight: bold;
        }
        .btn-primary:hover {
            opacity: 0.9;
        }
        .btn-outline-secondary {
            border-color: var(--border-color);
            color: var(--text-secondary);
        }
        .btn-outline-secondary:hover {
            background-color: var(--hover-color);
            color: var(--text-primary);
        }
    </style>
</head>
<body>
    <div class="py-4 mb-4 jumbotron-custom">
        <div class="container">
            <h1 class="display-5 fw-bold">🎬 Temukan Film Favoritmu di Sini</h1>
            <p class="lead">Temukan tontonan terbaik dari berbagai genre dan era — semua ada di sini.</p>
        </div>
    </div>

    <div class="container">
        <div class="p-4 rounded shadow-sm content-wrapper">
            
            <div class="row mb-3">
                <div class="col-md-10">
                    <label for="searchInput" class="form-label">Cari Film:</label>
                    <input type="text" id="searchInput" class="form-control" placeholder="Masukkan kata kunci...">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button id="searchButton" class="btn btn-primary w-100">Cari</button>
                </div>
            </div>

            <div class="row mb-4 g-3 align-items-end">
                <div class="col-md-3">
                    <label for="genreFilter" class="form-label">Filter Genre:</label>
                    <select id="genreFilter" class="form-select">
                        <option value="">Semua Genre</option>
                        @foreach ($genres as $genre)
                            <option value="{{ $genre }}">{{ $genre }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label for="ratingFilter" class="form-label">Filter Rating Film:</label>
                    <select id="ratingFilter" class="form-select">
                        <option value="">Semua Rating</option>
                        @foreach ($ratingRanges as $key => $text)
                            <option value="{{ $key }}">{{ $text }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-2">
                    <label for="startYear" class="form-label">Dari Tahun:</label>
                    <input type="number" id="startYear" class="form-control" placeholder="Contoh: 2015">
                </div>
                <div class="col-md-2">
                    <label for="endYear" class="form-label">Sampai Tahun:</label>
                    <input type="number" id="endYear" class="form-control" placeholder="Contoh: 2020">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button id="resetFilterBtn" class="btn btn-outline-secondary w-100">Reset Semua</button>
                </div>
            </div>

            <table id="moviesTable" class="table table-hover">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Tahun</th>
                        <th>Genre</th>
                        <th>Rating Film</th>
                        <th>Durasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($results) && !empty($results))
                        @foreach ($results as $result)
                            <tr data-rating="{{ $result['rating_film'] ?? 0 }}">
                                <td>{{ $result['judul'] ?? 'N/A' }}</td>
                                <td>{{ $result['tahun'] ?? 'N/A' }}</td>
                                <td>{{ $result['genre'] ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-success">
                                        <i class="fas fa-star"></i> {{ $result['rating_film'] ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>{{ $result['durasi'] ?? 'N/A' }}</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-info details-btn" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#movieModal"
                                        data-judul="{{ $result['judul'] ?? '' }}"
                                        data-tahun="{{ $result['tahun'] ?? '' }}"
                                        data-sinopsis="{{ $result['sinopsis'] ?? '' }}"
                                        data-genre="{{ $result['genre'] ?? '' }}"
                                        data-batas-usia="{{ $result['batas_usia'] ?? '' }}"
                                        data-rating-film="{{ $result['rating_film'] ?? '' }}"
                                        data-votes="{{ $result['votes'] ?? '' }}"
                                        data-bahasa="{{ $result['bahasa'] ?? '' }}"
                                        data-sutradara="{{ $result['sutradara'] ?? '' }}"
                                        data-aktor="{{ $result['aktor'] ?? '' }}"
                                        data-durasi="{{ $result['durasi'] ?? '' }}"
                                        data-score="{{ $result['score'] ?? '' }}">
                                        <i class="fas fa-info-circle"></i> Detail
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="movieModal" tabindex="-1" aria-labelledby="movieModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="movieModalLabel">Detail Film</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h3 id="modalMovieTitle"></h3>
                            <p class="text-muted" id="modalMovieYear"></p>
                            <p id="modalMovieDescription"></p>
                        </div>
                        <div class="col-md-4">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"><strong>Genre:</strong> <span id="modalMovieGenre"></span></li>
                                <li class="list-group-item"><strong>Batas Usia:</strong> <span id="modalMovieRating"></span></li>
                                <li class="list-group-item"><strong>User Rating:</strong> <span id="modalMovieUserRating"></span></li>
                                <li class="list-group-item"><strong>Votes:</strong> <span id="modalMovieVotes"></span></li>
                                <li class="list-group-item"><strong>Bahasa:</strong> <span id="modalMovieLanguages"></span></li>
                                <li class="list-group-item"><strong>Sutradara:</strong> <span id="modalMovieDirectors"></span></li>
                                <li class="list-group-item"><strong>Aktor:</strong> <span id="modalMovieActors"></span></li>
                                <li class="list-group-item"><strong>Durasi:</strong> <span id="modalMovieRuntime"></span></li>
                                <li class="list-group-item"><strong>Score:</strong> <span id="modalMovieScore"></span></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

    <script>
    $(document).ready(function () {
        const table = $('#moviesTable').DataTable({
            responsive: true,
            dom: 'rt<"d-flex justify-content-between mt-3"lip>',
            pageLength: 10,
            order: [[ 1, 'desc' ]] 
        });

        $.fn.dataTable.ext.search.push(
            function( settings, data, dataIndex ) {
                let genreFilter = $('#genreFilter').val();
                let ratingFilter = $('#ratingFilter').val();
                let startYearFilter = parseInt($('#startYear').val(), 10) || 0; 
                let endYearFilter = parseInt($('#endYear').val(), 10) || 9999; 

                let rowYear = parseInt(data[1]) || 0;
                let rowGenre = data[2];
                
                let rowNode = table.row(dataIndex).node();
                let rowRating = parseFloat($(rowNode).data('rating')) || 0;
                
                let ratingMatch = false;
                if (ratingFilter === "") {
                    ratingMatch = true;
                } else {
                    switch (ratingFilter) {
                        case '8': if (rowRating >= 8.0) ratingMatch = true; break;
                        case '7': if (rowRating >= 7.0 && rowRating < 8.0) ratingMatch = true; break;
                        case '6': if (rowRating >= 6.0 && rowRating < 7.0) ratingMatch = true; break;
                        case '5': if (rowRating < 6.0) ratingMatch = true; break;
                    }
                }

                if ( (genreFilter === "" || genreFilter === rowGenre) &&
                     ratingMatch &&
                     (rowYear >= startYearFilter && rowYear <= endYearFilter) )
                {
                    return true;
                }
                return false;
            }
        );
        
        $('#searchButton').on('click', function () {
            const query = $('#searchInput').val().trim();
            
            if (query !== "") {
                $('#searchButton').prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');

                $.ajax({
                    url: "/search",
                    method: "GET",
                    data: { q: query, rank: 1000 },
                    success: function (data) {
                        table.order([]).draw();
                        table.clear();
                        if (data && data.length > 0) {
                            data.forEach(function(result) {
                                var rowNode = table.row.add([
                                    result.judul,
                                    result.tahun,
                                    result.genre,
                                    `<span class="badge bg-success"><i class="fas fa-star"></i> ${result.rating_film}</span>`,
                                    result.durasi,
                                    `<button class="btn btn-sm btn-outline-info details-btn" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#movieModal"
                                        data-judul="${result.judul || ''}"
                                        data-tahun="${result.tahun || ''}"
                                        data-sinopsis="${result.sinopsis || ''}"
                                        data-genre="${result.genre || ''}"
                                        data-batas-usia="${result.batas_usia || ''}"
                                        data-rating-film="${result.rating_film || ''}"
                                        data-votes="${result.votes || ''}"
                                        data-bahasa="${result.bahasa || ''}"
                                        data-sutradara="${result.sutradara || ''}"
                                        data-aktor="${result.aktor || ''}"
                                        data-durasi="${result.durasi || ''}"
                                        data-score="${result.score || ''}">
                                        <i class="fas fa-info-circle"></i> Detail
                                    </button>`
                                ]).node();
                                $(rowNode).attr('data-rating', result.rating_film || 0);
                            });
                        }
                        table.draw();
                    },
                    error: function() {
                        alert('Gagal mengambil data dari server.');
                    },
                    complete: function() {
                        $('#searchButton').prop('disabled', false).html('Cari');
                        table.draw(); 
                    }
                });
            } else {
                table.column(0).search('').draw();
            }
        });

        $('#resetFilterBtn').on('click', function () {
            window.location.reload();
        });
        
        $('#moviesTable tbody').on('click', '.details-btn', function () {
            var movie = $(this).data();
            
            $('#modalMovieTitle').text(movie.judul);
            $('#modalMovieYear').text('(' + movie.tahun + ')');
            $('#modalMovieDescription').text(movie.sinopsis);
            $('#modalMovieGenre').text(movie.genre);
            $('#modalMovieRating').text(movie.batasUsia);
            $('#modalMovieUserRating').text(movie.ratingFilm);
            $('#modalMovieVotes').text(movie.votes);
            $('#modalMovieLanguages').text(movie.bahasa);
            $('#modalMovieDirectors').text(movie.sutradara);
            
            if (movie.aktor && typeof movie.aktor === 'string') {
                var aktorList = movie.aktor.replace(/[\[\]']/g, '').split(', ').join(', ');
                $('#modalMovieActors').text(aktorList);
            } else {
                 $('#modalMovieActors').text('N/A');
            }

            $('#modalMovieRuntime').text(movie.durasi);
            $('#modalMovieScore').text(typeof movie.score === 'number' ? movie.score.toFixed(4) : 'N/A');
        });
    });
    </script>
</body>
</html>