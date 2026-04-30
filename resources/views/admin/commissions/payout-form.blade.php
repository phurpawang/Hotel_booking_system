@extends('admin.layout')

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
        font-weight: 600;
        color: #333;
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }

    .form-control {
        width: 100%;
        padding: 0.8rem 1rem;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 0.95rem;
        font-family: inherit;
        transition: all 0.2s;
    }

    .form-control:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .form-control.error {
        border-color: #dc3545;
    }

    .error-message {
        color: #dc3545;
        font-size: 0.85rem;
        margin-top: 0.3rem;
    }

    .required {
        color: #dc3545;
        font-weight: bold;
    }

    .payout-summary {
        background: linear-gradient(135deg, #f8f9ff 0%, #f0f2ff 100%);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border-left: 4px solid #667eea;
    }

    .summary-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.8rem 0;
        border-bottom: 1px solid #e8eaf6;
    }

    .summary-item:last-child {
        border-bottom: none;
    }

    .summary-label {
        color: #666;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .summary-value {
        color: #333;
        font-weight: 700;
        font-size: 1rem;
    }

    .summary-value.amount {
        color: #667eea;
        font-size: 1.2rem;
    }

    .button-group {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }

    .btn {
        flex: 1;
        padding: 0.8rem 1.5rem;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        font-size: 0.95rem;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .btn-secondary {
        background: #f0f0f0;
        color: #333;
        border: 2px solid #e0e0e0;
    }

    .btn-secondary:hover {
        background: #e8e8e8;
    }

    .alert {
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }

    .alert-danger {
        background: #fee;
        color: #c33;
        border: 1px solid #fcc;
    }

    .alert-success {
        background: #efe;
        color: #3c3;
        border: 1px solid #cfc;
    }

    .page-header {
        margin-bottom: 2rem;
    }

    .page-title {
        font-size: 2rem;
        font-weight: 700;
        color: #333;
        margin: 0 0 0.5rem 0;
    }

    .page-subtitle {
        color: #999;
        margin: 0;
    }

    .help-text {
        font-size: 0.85rem;
        color: #999;
        margin-top: 0.3rem;
    }
</style>
@endpush

@section('content')
<div class="p-6">
    <!-- Page Header -->
    <div class="page-header">
        <h2 class="page-title">Mark Online Payout as Paid</h2>
        <p class="page-subtitle">Record the payment details for Hotel {{ $payout->hotel->name }} - Online Payments</p>
    </div>

    <!-- Form -->
    <div class="form-card">
        <!-- Payout Summary -->
        <div class="payout-summary">
            <div class="summary-item">
                <span class="summary-label">Hotel</span>
                <span class="summary-value">{{ $payout->hotel->name }}</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Period</span>
                <span class="summary-value">{{ date('F Y', mktime(0, 0, 0, $payout->month ?: 1, 1, $payout->year)) }}</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Total Bookings</span>
                <span class="summary-value">{{ $payout->total_bookings ?? 0 }}</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Online Guest Payments</span>
                <span class="summary-value amount">Nu. {{ number_format($payout->online_payment_amount ?? 0, 2) }}</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Commission Deducted (10%)</span>
                <span class="summary-value amount">Nu. {{ number_format($payout->online_commission_amount ?? 0, 2) }}</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Net Payout to Hotel</span>
                <span class="summary-value amount">Nu. {{ number_format($payout->online_payout_amount ?? 0, 2) }}</span>
            </div>
        </div>

        <!-- Error Messages -->
        @if($errors->any())
            <div class="alert alert-danger">
                <strong>Validation Error:</strong>
                <ul style="margin: 0.5rem 0 0 0; padding-left: 1.5rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Payment Form -->
        <form action="{{ route('admin.commissions.mark-paid', $payout->id) }}" method="POST">
            @csrf

            <!-- Payout Reference -->
            <div class="form-group">
                <label for="payout_reference" class="form-label">
                    Payout Reference <span class="required">*</span>
                </label>
                <input 
                    type="text" 
                    id="payout_reference" 
                    name="payout_reference" 
                    class="form-control @error('payout_reference') error @enderror"
                    placeholder="e.g., TXN-2026-04-001, Check #12345, Wire Transfer Reference"
                    value="{{ old('payout_reference') }}"
                    required
                >
                <div class="help-text">Bank transfer reference, check number, transaction ID, or other payment identifier</div>
                @error('payout_reference')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <!-- Payout Notes -->
            <div class="form-group">
                <label for="payout_notes" class="form-label">
                    Payment Notes (Optional)
                </label>
                <textarea 
                    id="payout_notes" 
                    name="payout_notes" 
                    rows="4"
                    class="form-control @error('payout_notes') error @enderror"
                    placeholder="Add any additional notes about this payment..."
                    maxlength="1000"
                >{{ old('payout_notes') }}</textarea>
                <div class="help-text">Max 1000 characters. Include any relevant details about the payment method, date, or special instructions.</div>
                @error('payout_notes')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="button-group">
                <a href="{{ route('admin.commissions.show', $payout->id) }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Mark as Paid
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Character counter for notes
    const notestextarea = document.getElementById('payout_notes');
    if (notestextarea) {
        const updateCounter = () => {
            const remaining = 1000 - notestextarea.value.length;
            // Update help text with character count
        };
        notestextarea.addEventListener('input', updateCounter);
    }
</script>
@endpush
