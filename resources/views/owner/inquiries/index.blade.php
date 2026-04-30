@extends('owner.layouts.app')

@section('title', 'Guest Questions')

@section('header')
    <h2 class="text-2xl font-bold text-gray-800">Guest Questions & Inquiries</h2>
    <p class="text-gray-600 text-sm">Manage all guest questions and inquiries</p>
@endsection

@section('content')
<!-- Guest Inquiries Container -->
<div style="max-width: 1400px; margin: 0 auto; padding: 2rem; background: linear-gradient(135deg, #f5f7fa 0%, #f9fafb 100%); min-height: 100vh;">
    
    <!-- Page Header -->
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 2.5rem; border-radius: 15px; margin-bottom: 2.5rem; box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3); animation: slideInDown 0.5s ease-out;">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <i class="fas fa-comments" style="font-size: 2.5rem; opacity: 0.95;"></i>
            <div>
                <h1 style="margin: 0; font-weight: 700; font-size: 2rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.2);">Guest Questions & Inquiries</h1>
                <p style="margin: 0.5rem 0 0 0; opacity: 0.9;">Manage all guest questions and inquiries</p>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div style="background: linear-gradient(135deg, #10b98115 0%, #06b6d415 100%); border-left: 5px solid #10b981; padding: 1.5rem; border-radius: 12px; margin-bottom: 2rem; animation: slideInUp 0.4s ease-out;">
        <div style="color: #059669; font-weight: 700;"><i class="fas fa-check-circle me-2"></i>{{ session('success') }}</div>
    </div>
    @endif

    <!-- Statistics Cards Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;">
        <!-- Total Questions Card -->
        <div style="background: white; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); overflow: hidden; transition: all 0.3s ease; animation: slideInUp 0.5s ease-out;" onmouseover="this.style.boxShadow='0 8px 25px rgba(59, 130, 246, 0.2)'; this.style.transform='translateY(-5px)'" onmouseout="this.style.boxShadow='0 4px 15px rgba(0,0,0,0.08)'; this.style.transform='translateY(0)'">
            <div style="background: linear-gradient(135deg, #3B82F6 0%, #1e40af 100%); color: white; padding: 1.5rem; display: flex; align-items: center; gap: 1rem;">
                <div style="width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.8rem;">
                    <i class="fas fa-comments"></i>
                </div>
                <div>
                    <p style="margin: 0; font-size: 0.85rem; opacity: 0.9; text-transform: uppercase; font-weight: 600;">Total Questions</p>
                    <p style="margin: 0.5rem 0 0 0; font-size: 1.8rem; font-weight: 700;">{{ $totalInquiries ?? 0 }}</p>
                </div>
            </div>
            <div style="padding: 1.25rem; color: #666; font-size: 0.9rem;">
                <i class="fas fa-inbox mr-1" style="color: #3B82F6;"></i>All inquiries
            </div>
        </div>

        <!-- Pending Answers Card -->
        <div style="background: white; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); overflow: hidden; transition: all 0.3s ease; animation: slideInUp 0.5s ease-out 0.1s both;" onmouseover="this.style.boxShadow='0 8px 25px rgba(249, 115, 22, 0.2)'; this.style.transform='translateY(-5px)'" onmouseout="this.style.boxShadow='0 4px 15px rgba(0,0,0,0.08)'; this.style.transform='translateY(0)'">
            <div style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); color: white; padding: 1.5rem; display: flex; align-items: center; gap: 1rem;">
                <div style="width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.8rem;">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <div>
                    <p style="margin: 0; font-size: 0.85rem; opacity: 0.9; text-transform: uppercase; font-weight: 600;">Pending Answers</p>
                    <p style="margin: 0.5rem 0 0 0; font-size: 1.8rem; font-weight: 700;">{{ $pendingCount ?? 0 }}</p>
                </div>
            </div>
            <div style="padding: 1.25rem; color: #666; font-size: 0.9rem;">
                <i class="fas fa-clock mr-1" style="color: #f97316;"></i>Awaiting response
            </div>
        </div>

        <!-- Answered Card -->
        <div style="background: white; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); overflow: hidden; transition: all 0.3s ease; animation: slideInUp 0.5s ease-out 0.2s both;" onmouseover="this.style.boxShadow='0 8px 25px rgba(34, 197, 94, 0.2)'; this.style.transform='translateY(-5px)'" onmouseout="this.style.boxShadow='0 4px 15px rgba(0,0,0,0.08)'; this.style.transform='translateY(0)'">
            <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 1.5rem; display: flex; align-items: center; gap: 1rem;">
                <div style="width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.8rem;">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    <p style="margin: 0; font-size: 0.85rem; opacity: 0.9; text-transform: uppercase; font-weight: 600;">Answered</p>
                    <p style="margin: 0.5rem 0 0 0; font-size: 1.8rem; font-weight: 700;">{{ $answeredCount ?? 0 }}</p>
                </div>
            </div>
            <div style="padding: 1.25rem; color: #666; font-size: 0.9rem;">
                <i class="fas fa-check mr-1" style="color: #10b981;"></i>Replied
            </div>
        </div>
    </div>

    <!-- Inquiries Table -->
    <div style="background: white; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); overflow: hidden; border: 1px solid #f0f0f0; animation: slideInUp 0.6s ease-out;">
        <!-- Header -->
        <div style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white; padding: 1.5rem;">
            <h3 style="margin: 0; font-size: 1.3rem; font-weight: 700;"><i class="fas fa-list mr-2"></i>Guest Inquiries</h3>
        </div>

        <!-- Content -->
        @if($inquiries->count() > 0)
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f9fafb; border-bottom: 2px solid #e5e7eb;">
                            <th style="padding: 1rem; text-align: left; font-weight: 700; color: #11998e; text-transform: uppercase; font-size: 0.85rem;">Guest Name</th>
                            <th style="padding: 1rem; text-align: left; font-weight: 700; color: #11998e; text-transform: uppercase; font-size: 0.85rem;">Email</th>
                            <th style="padding: 1rem; text-align: left; font-weight: 700; color: #11998e; text-transform: uppercase; font-size: 0.85rem;">Question</th>
                            <th style="padding: 1rem; text-align: center; font-weight: 700; color: #11998e; text-transform: uppercase; font-size: 0.85rem;">Status</th>
                            <th style="padding: 1rem; text-align: center; font-weight: 700; color: #11998e; text-transform: uppercase; font-size: 0.85rem;">Submitted</th>
                            <th style="padding: 1rem; text-align: center; font-weight: 700; color: #11998e; text-transform: uppercase; font-size: 0.85rem;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($inquiries as $inquiry)
                        <tr style="border-bottom: 1px solid #f0f0f0; transition: all 0.3s ease;" onmouseover="this.style.backgroundColor='#f9f9f9'" onmouseout="this.style.backgroundColor='white'">
                            <td style="padding: 1rem; color: #333;">
                                <div style="font-weight: 600;">{{ $inquiry->guest_name }}</div>
                            </td>
                            <td style="padding: 1rem;">
                                <a href="mailto:{{ $inquiry->guest_email }}" style="color: #667eea; text-decoration: none; font-weight: 500;">{{ $inquiry->guest_email }}</a>
                            </td>
                            <td style="padding: 1rem; color: #666;">
                                <div style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $inquiry->question }}">
                                    {{ Str::limit($inquiry->question, 60) }}
                                </div>
                            </td>
                            <td style="padding: 1rem; text-align: center;">
                                @if($inquiry->status === 'PENDING')
                                    <span style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); color: white; padding: 0.4rem 0.8rem; border-radius: 6px; font-weight: 600; font-size: 0.85rem; display: inline-block;">
                                        <i class="fas fa-hourglass-half mr-1"></i>Pending
                                    </span>
                                @elseif($inquiry->status === 'ANSWERED')
                                    <span style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 0.4rem 0.8rem; border-radius: 6px; font-weight: 600; font-size: 0.85rem; display: inline-block;">
                                        <i class="fas fa-check-circle mr-1"></i>Answered
                                    </span>
                                @else
                                    <span style="background: #f3f4f6; color: #6b7280; padding: 0.4rem 0.8rem; border-radius: 6px; font-weight: 600; font-size: 0.85rem; display: inline-block;">
                                        <i class="fas fa-times-circle mr-1"></i>Closed
                                    </span>
                                @endif
                            </td>
                            <td style="padding: 1rem; text-align: center; color: #666; font-size: 0.9rem;">
                                {{ $inquiry->created_at->format('M d, Y') }}
                            </td>
                            <td style="padding: 1rem; text-align: center;">
                                <a href="{{ route('owner.inquiries.show', $inquiry->id) }}" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 0.6rem 1rem; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 0.5rem; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(102, 126, 234, 0.3)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                                    <i class="fas fa-eye"></i>View
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($inquiries->hasPages())
            <div style="padding: 1.5rem; border-top: 1px solid #f0f0f0; display: flex; justify-content: center;">
                {{ $inquiries->links() }}
            </div>
            @endif
        @else
            <!-- Empty State -->
            <div style="text-align: center; padding: 3rem 2rem;">
                <div style="font-size: 4rem; margin-bottom: 1rem; opacity: 0.2;">
                    <i class="fas fa-comments"></i>
                </div>
                <h4 style="font-size: 1.25rem; color: #333; margin-bottom: 0.5rem; font-weight: 700;">No guest inquiries yet.</h4>
                <p style="color: #666; margin: 0; font-size: 0.95rem;">When guests ask questions about your hotel, they will appear here.</p>
                <p style="color: #999; margin: 1rem 0 0 0; font-size: 0.9rem;">You'll be able to view and respond to all inquiries from this dashboard.</p>
            </div>
        @endif
    </div>
</div>

<style>
    @keyframes slideInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endsection
