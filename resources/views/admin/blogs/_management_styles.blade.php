@include('admin.blogs._editor_styles')
<style>
    .blog-admin-editor .table-responsive { width: 100%; overflow-x: auto; }
    .blog-admin-editor .table { width: 100%; min-width: 760px; border-collapse: collapse; text-align: left; }
    .blog-admin-editor .table th { padding: .8rem 1rem; border-bottom: 1px solid #374151; background: #0c0f15; color: #6b7280; font-size: .65rem; font-weight: 800; letter-spacing: .08em; text-transform: uppercase; }
    .blog-admin-editor .table td { padding: .9rem 1rem; border-bottom: 1px solid #1f2937; color: #d1d5db; font-size: .78rem; vertical-align: middle; }
    .blog-admin-editor .table tbody tr:hover { background: rgba(55,65,81,.22); }
    .blog-admin-editor .text-center { text-align: center; }
    .blog-admin-editor .text-right { text-align: right; }
    .blog-admin-editor .img-thumbnail { max-width: 5rem; border: 1px solid #374151; background: #0c0f15; padding: .15rem; }
    .blog-admin-editor .btn-info { border-color: rgba(59,130,246,.5); color: #60a5fa; background: transparent; }
    .blog-admin-editor .btn-danger { border-color: rgba(239,68,68,.45); color: #f87171; background: transparent; }
    .blog-admin-editor .btn-success { border-color: rgba(34,197,94,.45); color: #4ade80; background: transparent; }
    .blog-admin-editor .btn-xs { min-height: 1.8rem; padding: .3rem .55rem; font-size: .58rem; }
    .blog-admin-editor .d-inline { display: inline-block; }
    .blog-admin-editor .alert { margin-bottom: 1rem; padding: .8rem 1rem; border: 1px solid rgba(34,197,94,.35); background: rgba(20,83,45,.22); color: #4ade80; font-size: .78rem; }
    .blog-admin-editor .close { margin-left: auto; border: 0; background: none; color: #9ca3af; cursor: pointer; font-size: 1.25rem; }
    .blog-admin-editor .modal { position: fixed; inset: 0; z-index: 80; display: none; overflow-y: auto; padding: 1rem; background: rgba(0,0,0,.78); }
    .blog-admin-editor .modal.show { display: flex; align-items: flex-start; justify-content: center; }
    .blog-admin-editor .modal-dialog { width: min(100%, 720px); margin: 4vh auto; }
    .blog-admin-editor .modal-content { border: 1px solid #374151; background: #111827; box-shadow: 0 25px 60px rgba(0,0,0,.5); }
    .blog-admin-editor .modal-header, .blog-admin-editor .modal-footer { display: flex; align-items: center; gap: .75rem; padding: 1rem 1.25rem; border-bottom: 1px solid #1f2937; }
    .blog-admin-editor .modal-footer { justify-content: flex-end; border-top: 1px solid #1f2937; border-bottom: 0; }
    .blog-admin-editor .modal-body { padding: 1.25rem; }
    .blog-admin-editor .modal-title { color: #fff; font-size: 1rem; font-weight: 700; }
</style>
<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.blog-admin-editor [data-toggle="modal"]').forEach(trigger => {
        trigger.addEventListener('click', () => {
            const modal = document.querySelector(trigger.dataset.target);
            modal?.classList.add('show');
            modal?.setAttribute('aria-hidden', 'false');
        });
    });
    document.querySelectorAll('.blog-admin-editor [data-dismiss="modal"]').forEach(trigger => {
        trigger.addEventListener('click', () => {
            trigger.closest('.modal')?.classList.remove('show');
        });
    });
    document.querySelectorAll('.blog-admin-editor [data-dismiss="alert"]').forEach(trigger => {
        trigger.addEventListener('click', () => trigger.closest('.alert')?.remove());
    });
    document.querySelectorAll('.blog-admin-editor .modal').forEach(modal => {
        modal.addEventListener('click', event => {
            if (event.target === modal) modal.classList.remove('show');
        });
    });
    document.addEventListener('keydown', event => {
        if (event.key === 'Escape') document.querySelectorAll('.blog-admin-editor .modal.show').forEach(modal => modal.classList.remove('show'));
    });
});
</script>
