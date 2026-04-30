@extends('admin.layout')

@section('title', 'Mark Offline Commission as Received')

@push('styles')
<style>
    .form-card {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        max-width: 600px;
        margin: 0 auto;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: #333;
    }

    .form-label .required {
        color: #dc2626;
    }

    .form-input {
        width: 100%;
        padding: 0.75rem;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.2s;
    }

    .form-input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .form-textarea {
        resize: vertical;
        min-height: 100px;
    }

    .summary-card {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        padding: 1.5rem;
        border-radius: 12px;
        margin-bottom: 2rem;
    }

    .summary-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.3);
    }

    .summary-item:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }

    .summary-label {
        font-weight: 600;
        color: #333;
    }

    .summary-value {
        font-size: 1.2rem;
        font-weight: 700;
        color: #f5576c;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.6rem 1.2rem;
        border-radius: 8px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.2s;
        margin-bottom: 1.5rem;
    }

    .btn-back:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .button-group {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }

    .btn-submit {
        flex: 1;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 8px;
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: white;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(17, 153, 142, 0.3);
    }

    .btn-cancel {
        flex: 1;
        padding: 0.75rem 1.5rem;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        background: white;
        color: #333;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-cancel:hover {
        border-color: #d1d5db;
        background: #f9fafb;
    }

    .page-title {
        font-size: 2rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 0.5rem;
    }

    .page-subtitle {
        color: #999;
        margin-bottom: 2rem;
    }

    .alert {
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }

    .alert-info {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
</style>
@endpush

@section('content')
<div class="p-6">
    <a href="{{ route('admin.commissions.show', $payout->id) }}" class="btn-back">
        <i class="bi bi-arrow-left"></i> Back to Commission Details
    </a>

    <div class="form-card">
        <h1 class="page-title">Mark Offline Commission as Received</h1>
        <p class="page-subtitle">{{ $payout->hotel->name ?? 'Hotel' }} - {{ date('F Y', mktime(0, 0, 0, $payout->month ?: 1, 1, $payout->year)) }}</p>

        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> The hotel has collected payment directly from guests. Please confirm receipt of the commission.
        </div>

        <!-- Commission Summary -->
        <div class="summary-card">
            <div class="summary-item">
                <span class="summary-label">Amount Collected by Hotel</span>
                <span class="summary-value">Nu. {{ number_format($payout->offline_payment_amount, 2) }}</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Commission Due from Hotel (10%)</span>
                <span class="summary-value">Nu. {{ number_format($payout->offline_commission_due, 2) }}</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Period</span>
                <span class="summary-value">{{ date('F Y', mktime(0, 0, 0, $payout->month ?: 1, 1, $payout->year)) }}</span>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('admin.commissions.mark-offline-commission', $payout->id) }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label class="form-label">
                    Reference Number <span class="required">*</span>
                </label>
                <input 
                    type="text" 
                    name="offline_commission_reference" 
                    class="form-input @error('offline_commission_reference') is-invalid @enderror"
                    placeholder="e.g., CHQ-2026-001, TRF-APR-2026"
                    value="{{ old('offline_commission_reference') }}"
                    required
                >
                @error('offline_commission_reference')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small style="color: #999; display: block; margin-top: 0.5rem;">
                    <i class="bi bi-info-circle"></i> Enter the check number, bank transfer reference, or any other transaction identifier
                </small>
            </div>

            <div class="form-group">
                <label class="form-label">
                    Notes (Optional)
                </label>
                <textarea 
                    name="payout_notes" 
                    class="form-input form-textarea @error('payout_notes') is-invalid @enderror"
                    placeholder="Add any additional notes about this commission payment..."
                >{{ old('payout_notes') }}</textarea>
                @error('payout_notes')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small style="color: #999; display: block; margin-top: 0.5rem;">
                    <i class="bi bi-info-circle"></i> e.g., "Received via bank transfer", "Collected in cash", etc.
                </small>
            </div>

            <div class="button-group">
                <button type="submit" class="btn-submit">
                    <i class="bi bi-check-circle"></i> Confirm Commission Receipt
                </button>
                <a href="{{ route('admin.commissions.show', $payout->id) }}" class="btn-cancel">
                    <i class="bi bi-x-circle"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
