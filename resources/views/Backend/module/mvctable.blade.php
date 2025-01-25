@extends('Backend.layouts.app')
@section('content')
<style>
    :root{
        --back: whitesmoke;
        --text: #2c3e50;
        --texth2: #6c757d;
    }
    
    .dark{
        --back: #333;
        --text: white;
        --texth2: white;
    }

    .module-selection {
        background: var(--back);
        padding: 2rem;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        margin: 2rem auto;
        max-width: 800px;
    }

    .module-header {
        text-align: center;
        margin-bottom: 2.5rem;
        position: relative;
        padding-bottom: 1rem;
    }

    .module-header h2 {
        color: var(--text);
        font-size: 2rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .module-header::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 4px;
        background: linear-gradient(90deg, #3498db, #2980b9);
        border-radius: 2px;
    }

    /* Updated grid container with scrollable area */
    .columns-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
        max-height: 400px; /* Adjust this value based on your needs */
        overflow-y: auto;
        padding-right: 10px; /* Add padding for scrollbar */
    }

    /* Styling the scrollbar */
    .columns-grid::-webkit-scrollbar {
        width: 8px;
    }

    .columns-grid::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }

    .columns-grid::-webkit-scrollbar-thumb {
        background: #3498db;
        border-radius: 4px;
    }

    .columns-grid::-webkit-scrollbar-thumb:hover {
        background: #2980b9;
    }

    .column-item {
        background: white;
        padding: 1rem;
        border-radius: 8px;
        border: 1px solid #e1e8ed;
        transition: all 0.3s ease;
        /* Ensure the item stays as one unit */
        position: relative;
        display: flex;
        align-items: center;
    }

    .column-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        border-color: #3498db;
    }

    .custom-checkbox {
        display: flex;
        align-items: center;
        width: 100%;
        cursor: pointer;
        /* Keep checkbox and label together */
        position: relative;
    }

    .custom-checkbox input[type="checkbox"] {
        width: 18px;
        height: 18px;
        margin-right: 10px;
        cursor: pointer;
        /* Keep checkbox aligned with label */
        flex-shrink: 0;
    }

    .custom-checkbox label {
        color: #34495e;
        font-size: 0.95rem;
        cursor: pointer;
        user-select: none;
        margin: 0;
        /* Prevent label from wrapping awkwardly */
        flex: 1;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .submit-btn {
        display: block;
        width: 100%;
        max-width: 200px;
        margin: 2rem auto 0;
        padding: 0.8rem 1.5rem;
        background: linear-gradient(135deg, #3498db, #2980b9);
        color: white;
        border: none;
        border-radius: 25px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
        cursor: pointer;
        position: sticky;
        bottom: 0;
    }

    .submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
        background: linear-gradient(135deg, #2980b9, #3498db);
    }

    .text-muted {
        color: var(--texth2) !important;
    }

    @media (max-width: 768px) {
        .module-selection {
            padding: 1.5rem;
            margin: 1rem;
        }

        .module-header h2 {
            font-size: 1.75rem;
        }

        .columns-grid {
            grid-template-columns: 1fr;
            max-height: 60vh; /* Adjust for mobile */
        }
    }
</style>

<div class="module-selection">
    <div class="container">
        <div class="module-header">
            <h2>Table View</h2>
            <p class="text-muted">Choose the columns you want to include</p>
        </div>

        <form action="/createmvc" method="POST">
            @csrf
            <div class="columns-grid">
                @foreach ($columns as $column)
                <div class="column-item">
                    <div class="custom-checkbox">
                        <input 
                            type="checkbox" 
                            class="form-check-input" 
                            id="column_{{ $loop->index }}" 
                            name="columns[]" 
                            value="{{ $column }}"
                        >
                        <label class="form-check-label" for="column_{{ $loop->index }}">
                            {{ $column }}
                        </label>
                    </div>
                </div>
                @endforeach
            </div>
            
            <input type="hidden" name="moduleId" value="{{old('moduleId',$moduleId)}}" readonly>
            <input type="hidden" name="tablename" value="{{old('tablename',$tablename)}}" readonly>

            <button type="submit" class="submit-btn">
                Submit Selection
            </button>
        </form>
    </div>
</div>
@endsection