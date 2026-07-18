import React, { useState, useEffect } from 'react';
import { navigateTo } from '../navigation';
import { LucideIcon } from './LucideIcon';

interface Question {
  id: number;
  question: string;
  help_text: string | null;
  question_type: string;
  options: string[] | null;
  is_required: boolean;
  sort_order: number;
}

interface Job {
  id: number;
  title: string;
  slug: string;
  short_description: string | null;
  description: string | null;
  responsibilities: string | null;
  educational_requirements: string | null;
  experience_requirements: string | null;
  additional_requirements: string | null;
  skills: string | null;
  benefits: string | null;
  location: string;
  gender?: string;
  vacancy: number;
  salary_min: string | null;
  salary_max: string | null;
  salary_type: string;
  application_deadline: string | null;
  featured_image: string | null;
  is_featured: boolean;
  department?: { id: number; name: string };
  employment_type?: { id: number; name: string };
  questions: Question[];
  show_address?: boolean;
  show_linkedin?: boolean;
  show_portfolio?: boolean;
  show_current_company?: boolean;
  show_current_designation?: boolean;
  show_expected_salary?: boolean;
  show_joining_date?: boolean;
  show_cover_letter?: boolean;
}

const getImageUrl = (url: string | null) => {
  if (!url) return '';
  if (url.startsWith('http://') || url.startsWith('https://') || url.startsWith('/')) {
    return url;
  }
  return '/' + url;
};

const formatDate = (dateStr: string | null) => {
  if (!dateStr) return '';
  try {
    if (dateStr.includes('T')) {
      return dateStr.split('T')[0];
    }
    return dateStr;
  } catch (e) {
    return dateStr;
  }
};

interface JobDetailPageProps {
  slug: string;
}

export const JobDetailPage: React.FC<JobDetailPageProps> = ({ slug }) => {
  const [job, setJob] = useState<Job | null>(null);
  const [loading, setLoading] = useState(true);

  // Form submission states
  const [submitting, setSubmitting] = useState(false);
  const [errorMsg, setErrorMsg] = useState<string | null>(null);
  const [successMsg, setSuccessMsg] = useState<string | null>(null);
  const [referenceId, setReferenceId] = useState<string | null>(null);

  // Form input states
  const [fullName, setFullName] = useState('');
  const [email, setEmail] = useState('');
  const [phone, setPhone] = useState('');
  const [presentAddress, setPresentAddress] = useState('');
  const [linkedinUrl, setLinkedinUrl] = useState('');
  const [portfolioUrl, setPortfolioUrl] = useState('');
  const [currentCompany, setCurrentCompany] = useState('');
  const [currentDesignation, setCurrentDesignation] = useState('');
  const [expectedSalary, setExpectedSalary] = useState('');
  const [joiningDate, setJoiningDate] = useState('');
  const [coverLetter, setCoverLetter] = useState('');
  const [cvFile, setCvFile] = useState<File | null>(null);
  const [consent, setConsent] = useState(false);

  // Dynamic answers
  const [answers, setAnswers] = useState<Record<string, any>>({});

  useEffect(() => {
    const fetchJobDetails = async () => {
      try {
        const res = await fetch(`/api/careers/${slug}`);
        if (res.ok) {
          const data = await res.json();
          setJob(data);
        } else {
          setErrorMsg("Job opening not found or expired.");
        }
      } catch (err) {
        setErrorMsg("Failed to connect to the recruitment API.");
      } finally {
        setLoading(false);
      }
    };
    fetchJobDetails();
  }, [slug]);

  const handleFileChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const file = e.target.files?.[0];
    if (file) {
      const ext = file.name.split('.').pop()?.toLowerCase();
      if (!ext || !['pdf', 'doc', 'docx'].includes(ext)) {
        alert("Invalid file format. Please upload PDF, DOC, or DOCX only.");
        e.target.value = '';
        return;
      }
      if (file.size > 5120 * 1024) {
        alert("Maximum file size allowed is 5MB.");
        e.target.value = '';
        return;
      }
      setCvFile(file);
    }
  };

  const handleCustomFileChange = (questionId: number, e: React.ChangeEvent<HTMLInputElement>) => {
    const file = e.target.files?.[0];
    if (file) {
      if (file.size > 5120 * 1024) {
        alert("Maximum file size allowed is 5MB.");
        e.target.value = '';
        return;
      }
      setAnswers(prev => ({ ...prev, [questionId]: file }));
    }
  };

  const handleCustomTextChange = (questionId: number, val: any) => {
    setAnswers(prev => ({ ...prev, [questionId]: val }));
  };

  const handleCustomCheckboxChange = (questionId: number, option: string, checked: boolean) => {
    const current = answers[questionId] || [];
    let updated = [...current];
    if (checked) {
      updated.push(option);
    } else {
      updated = updated.filter(o => o !== option);
    }
    setAnswers(prev => ({ ...prev, [questionId]: updated }));
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!consent) {
      setErrorMsg("You must consent to the privacy policy parameters.");
      return;
    }
    if (!cvFile) {
      setErrorMsg("Please upload your CV or resume attachment.");
      return;
    }
    if (!job) return;

    setSubmitting(true);
    setErrorMsg(null);

    try {
      const formData = new FormData();
      formData.append('full_name', fullName);
      formData.append('email', email);
      formData.append('phone', phone);
      formData.append('present_address', presentAddress);
      formData.append('linkedin_url', linkedinUrl);
      formData.append('portfolio_url', portfolioUrl);
      formData.append('current_company', currentCompany);
      formData.append('current_designation', currentDesignation);
      if (expectedSalary) formData.append('expected_salary', expectedSalary);
      formData.append('available_joining_date', joiningDate);
      formData.append('cover_letter', coverLetter);
      formData.append('cv', cvFile);

      // Append custom answers
      job.questions.forEach((q) => {
        const val = answers[q.id];
        if (q.question_type === 'file') {
          if (val instanceof File) {
            formData.append(`question_${q.id}`, val);
          }
        } else if (Array.isArray(val)) {
          val.forEach(item => {
            formData.append(`question_${q.id}[]`, item);
          });
        } else if (val !== undefined && val !== null) {
          formData.append(`question_${q.id}`, val);
        }
      });

      const res = await fetch(`/api/careers/${job.id}/apply`, {
        method: 'POST',
        body: formData,
        headers: {
          'Accept': 'application/json'
        }
      });

      const data = await res.json();
      if (res.ok) {
        setSuccessMsg(data.message || "Application submitted successfully!");
        setReferenceId(data.reference_number);
        // Clear fields
        setFullName('');
        setEmail('');
        setPhone('');
        setPresentAddress('');
        setLinkedinUrl('');
        setPortfolioUrl('');
        setCurrentCompany('');
        setCurrentDesignation('');
        setExpectedSalary('');
        setJoiningDate('');
        setCoverLetter('');
        setCvFile(null);
        setConsent(false);
        setAnswers({});
      } else {
        setErrorMsg(data.error || data.message || "Failed to submit application. Make sure required fields are filled.");
      }
    } catch (err) {
      setErrorMsg("Failed to connect to recruitment server.");
    } finally {
      setSubmitting(false);
    }
  };

  if (loading) {
    return (
      <div className="text-center py-40 bg-salon-black min-h-screen text-white">
        <div className="inline-block animate-spin rounded-full h-8 w-8 border-2 border-t-gold-400 border-white/10"></div>
        <p className="text-xs text-gray-500 font-mono uppercase tracking-widest mt-4">Loading job details...</p>
      </div>
    );
  }

  if (!job) {
    return (
      <div className="text-center py-40 bg-salon-black min-h-screen text-white space-y-4">
        <LucideIcon name="AlertTriangle" className="text-red-500 mx-auto" size={40} />
        <h3 className="font-serif text-lg uppercase text-white">Opening Unavailable</h3>
        <p className="text-xs text-gray-500">{errorMsg || "This job opening has expired or is no longer active."}</p>
        <button onClick={() => navigateTo('/career')} className="px-6 py-2.5 bg-gold-400 text-black text-xs font-serif font-bold uppercase tracking-widest">
          View Active Careers
        </button>
      </div>
    );
  }

  return (
    <div className="w-full bg-salon-black min-h-screen text-white pt-24 pb-20 relative overflow-hidden">
      {/* Glow backgrounds */}
      <div className="absolute top-[10%] left-[-15%] h-[500px] w-[500px] rounded-full bg-gold-400/5 blur-[120px] pointer-events-none"></div>

      <div className="max-w-4xl mx-auto px-4 md:px-8 relative z-10 space-y-12">
        {/* Back Link */}
        <button onClick={() => navigateTo('/career')} className="inline-flex items-center text-xs font-bold uppercase tracking-widest text-gold-400 hover:underline">
          <LucideIcon name="ChevronLeft" size={14} className="mr-1" /> Back to Careers
        </button>

        {/* Job Heading Details */}
        <div className="bg-[#141414] border border-white/5 p-8 space-y-6">
          {job.featured_image && (
            <div className="aspect-[21/9] w-full overflow-hidden bg-salon-gray mb-6">
              <img src={getImageUrl(job.featured_image)} alt={job.title} className="w-full h-full object-cover brightness-95" />
            </div>
          )}

          <div className="space-y-2 text-left">
            <span className="text-[9px] font-mono text-gold-400 uppercase tracking-[0.25em] block">
              {job.department ? job.department.name : 'ADONIS DECK'} • {job.employment_type ? job.employment_type.name : 'FULL TIME'}
            </span>
            <h1 className="font-serif text-2xl sm:text-3xl uppercase tracking-wider text-white">
              {job.title}
            </h1>
            <p className="text-xs text-gray-500 font-mono">Location: {job.location} • Vacancy: {job.vacancy} position(s)</p>
          </div>

          <div className="grid grid-cols-2 sm:grid-cols-5 gap-4 border-t border-white/5 pt-6 text-[10px] font-mono text-gray-400 uppercase tracking-widest text-left">
            <div>
              <span className="text-gray-600 block mb-0.5">Salary Tier</span>
              <span className="text-white font-semibold">
                {job.salary_min && job.salary_max
                  ? `${parseInt(job.salary_min)} - ${parseInt(job.salary_max)} BDT`
                  : job.salary_type}
              </span>
            </div>
            <div>
              <span className="text-gray-600 block mb-0.5">Employment</span>
              <span className="text-white font-semibold">{job.employment_type ? job.employment_type.name : 'Full-Time'}</span>
            </div>
            <div>
              <span className="text-gray-600 block mb-0.5">Lounges Assigned</span>
              <span className="text-white font-semibold">{job.location.split(',')[0]}</span>
            </div>
            <div>
              <span className="text-gray-600 block mb-0.5">Gender Req</span>
              <span className="text-white font-semibold">{job.gender || 'Both'}</span>
            </div>
            <div>
              <span className="text-gray-600 block mb-0.5">Deadline</span>
              <span className="text-white font-semibold">{formatDate(job.application_deadline) || 'Open'}</span>
            </div>
          </div>
        </div>

        {/* Job Long description & requirements */}
        <div className="bg-[#141414] border border-white/5 p-8 space-y-6 text-left">
          {job.description && (
            <div className="space-y-3">
              <h3 className="font-serif text-sm uppercase tracking-widest text-white border-l-2 border-gold-400 pl-3">Job Description</h3>
              <div
                className="text-xs text-gray-300 leading-relaxed font-light space-y-3 prose prose-invert max-w-none"
                dangerouslySetInnerHTML={{ __html: job.description }}
              />
            </div>
          )}

          {job.responsibilities && (
            <div className="space-y-3 border-t border-white/5 pt-6">
              <h3 className="font-serif text-sm uppercase tracking-widest text-white border-l-2 border-gold-400 pl-3">Key Responsibilities</h3>
              <div
                className="text-xs text-gray-300 leading-relaxed font-light space-y-2 prose prose-invert max-w-none"
                dangerouslySetInnerHTML={{ __html: job.responsibilities }}
              />
            </div>
          )}

          <div className="grid grid-cols-1 md:grid-cols-2 gap-6 border-t border-white/5 pt-6">
            {job.educational_requirements && (
              <div className="space-y-2">
                <h3 className="font-serif text-xs uppercase tracking-widest text-white border-l-2 border-gold-400 pl-2">Education</h3>
                <div
                  className="text-xs text-gray-400 leading-relaxed font-light prose prose-invert"
                  dangerouslySetInnerHTML={{ __html: job.educational_requirements }}
                />
              </div>
            )}

            {job.experience_requirements && (
              <div className="space-y-2">
                <h3 className="font-serif text-xs uppercase tracking-widest text-white border-l-2 border-gold-400 pl-2">Experience</h3>
                <div
                  className="text-xs text-gray-400 leading-relaxed font-light prose prose-invert"
                  dangerouslySetInnerHTML={{ __html: job.experience_requirements }}
                />
              </div>
            )}
          </div>

          <div className="grid grid-cols-1 md:grid-cols-2 gap-6 border-t border-white/5 pt-6">
            {job.additional_requirements && (
              <div className="space-y-2">
                <h3 className="font-serif text-xs uppercase tracking-widest text-white border-l-2 border-gold-400 pl-2">Additional Parameters</h3>
                <div
                  className="text-xs text-gray-400 leading-relaxed font-light prose prose-invert"
                  dangerouslySetInnerHTML={{ __html: job.additional_requirements }}
                />
              </div>
            )}

            {job.benefits && (
              <div className="space-y-2">
                <h3 className="font-serif text-xs uppercase tracking-widest text-white border-l-2 border-gold-400 pl-2">Compensation & Benefits</h3>
                <div
                  className="text-xs text-gray-400 leading-relaxed font-light prose prose-invert"
                  dangerouslySetInnerHTML={{ __html: job.benefits }}
                />
              </div>
            )}
          </div>

          {job.skills && (
            <div className="border-t border-white/5 pt-6 space-y-2">
              <h3 className="font-serif text-xs uppercase tracking-widest text-white">Required Skills</h3>
              <div className="flex flex-wrap gap-2 pt-1">
                {job.skills.split(',').map((skill) => (
                  <span key={skill} className="bg-salon-gray border border-white/10 text-gray-300 text-[10px] font-mono uppercase tracking-wider px-3 py-1">
                    {skill.trim()}
                  </span>
                ))}
              </div>
            </div>
          )}
        </div>

        {/* Application Submission Form */}
        <div id="apply-section" className="bg-[#141414] border border-white/5 p-8 text-left space-y-6">
          <div className="border-b border-white/5 pb-4">
            <h3 className="font-serif text-lg uppercase tracking-wider text-white">Application Dispatch</h3>
            <p className="text-[10px] text-gray-500 uppercase tracking-widest mt-1">Submit credentials for secure evaluation</p>
          </div>

          {errorMsg && (
            <div className="p-4 bg-red-950/20 border border-red-800/40 text-xs text-red-400">
              {errorMsg}
            </div>
          )}

          {successMsg ? (
            <div className="p-6 bg-gold-400/5 border border-gold-400/20 text-center space-y-4">
              <LucideIcon name="Check" className="text-gold-400 mx-auto" size={40} />
              <h4 className="font-serif text-sm uppercase text-white tracking-wide">Application Submitted</h4>
              <p className="text-xs text-gray-400 max-w-md mx-auto leading-relaxed">
                Thank you. Your candidate registry profile has been transmitted successfully. Your reference code is:
              </p>
              <div className="font-mono text-white text-base bg-salon-gray border border-white/5 py-2.5 max-w-xs mx-auto font-bold tracking-widest select-all">
                {referenceId}
              </div>
              <p className="text-[10px] text-gray-500">A confirmation dispatch has been routed to your registered email address.</p>
              <button
                onClick={() => navigateTo('/career')}
                className="px-6 py-2.5 border border-gold-400/20 text-white hover:border-gold-400 text-[10px] font-mono uppercase tracking-widest transition-colors mt-2"
              >
                Back to Listings
              </button>
            </div>
          ) : (
            <form onSubmit={handleSubmit} className="space-y-6">
              {/* Standard Information */}
              <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label className="block text-[10px] font-mono uppercase tracking-widest text-gray-400 mb-1.5">Full Name *</label>
                  <input
                    type="text"
                    required
                    value={fullName}
                    onChange={(e) => setFullName(e.target.value)}
                    placeholder="e.g. Zaynul Abedin"
                    className="w-full bg-salon-gray text-white text-xs border border-white/10 px-4 py-3 focus:outline-none focus:border-gold-400 transition-colors"
                  />
                </div>

                <div>
                  <label className="block text-[10px] font-mono uppercase tracking-widest text-gray-400 mb-1.5">Email Address *</label>
                  <input
                    type="email"
                    required
                    value={email}
                    onChange={(e) => setEmail(e.target.value)}
                    placeholder="e.g. zaynul@example.com"
                    className="w-full bg-salon-gray text-white text-xs border border-white/10 px-4 py-3 focus:outline-none focus:border-gold-400 transition-colors"
                  />
                </div>

                <div>
                  <label className="block text-[10px] font-mono uppercase tracking-widest text-gray-400 mb-1.5">Phone Number *</label>
                  <input
                    type="tel"
                    required
                    value={phone}
                    onChange={(e) => setPhone(e.target.value)}
                    placeholder="e.g. +880 1700-600333"
                    className="w-full bg-salon-gray text-white text-xs border border-white/10 px-4 py-3 focus:outline-none focus:border-gold-400 transition-colors"
                  />
                </div>

                {job.show_joining_date !== false && (
                  <div>
                    <label className="block text-[10px] font-mono uppercase tracking-widest text-gray-400 mb-1.5">Available Joining Date</label>
                    <input
                      type="date"
                      value={joiningDate}
                      onChange={(e) => setJoiningDate(e.target.value)}
                      className="w-full bg-salon-gray text-white text-xs border border-white/10 px-4 py-3 focus:outline-none focus:border-gold-400 transition-colors"
                    />
                  </div>
                )}

                {job.show_linkedin !== false && (
                  <div>
                    <label className="block text-[10px] font-mono uppercase tracking-widest text-gray-400 mb-1.5">LinkedIn Profile URL</label>
                    <input
                      type="url"
                      value={linkedinUrl}
                      onChange={(e) => setLinkedinUrl(e.target.value)}
                      placeholder="https://linkedin.com/in/username"
                      className="w-full bg-salon-gray text-white text-xs border border-white/10 px-4 py-3 focus:outline-none focus:border-gold-400 transition-colors"
                    />
                  </div>
                )}

                {job.show_portfolio !== false && (
                  <div>
                    <label className="block text-[10px] font-mono uppercase tracking-widest text-gray-400 mb-1.5">Portfolio Link (Optional)</label>
                    <input
                      type="url"
                      value={portfolioUrl}
                      onChange={(e) => setPortfolioUrl(e.target.value)}
                      placeholder="https://portfolio.com"
                      className="w-full bg-salon-gray text-white text-xs border border-white/10 px-4 py-3 focus:outline-none focus:border-gold-400 transition-colors"
                    />
                  </div>
                )}

                {job.show_current_company !== false && (
                  <div>
                    <label className="block text-[10px] font-mono uppercase tracking-widest text-gray-400 mb-1.5">Current Company</label>
                    <input
                      type="text"
                      value={currentCompany}
                      onChange={(e) => setCurrentCompany(e.target.value)}
                      placeholder="e.g. Royal Barber Lounge"
                      className="w-full bg-salon-gray text-white text-xs border border-white/10 px-4 py-3 focus:outline-none focus:border-gold-400 transition-colors"
                    />
                  </div>
                )}

                {job.show_current_designation !== false && (
                  <div>
                    <label className="block text-[10px] font-mono uppercase tracking-widest text-gray-400 mb-1.5">Current Designation</label>
                    <input
                      type="text"
                      value={currentDesignation}
                      onChange={(e) => setCurrentDesignation(e.target.value)}
                      placeholder="e.g. Stylist Associate"
                      className="w-full bg-salon-gray text-white text-xs border border-white/10 px-4 py-3 focus:outline-none focus:border-gold-400 transition-colors"
                    />
                  </div>
                )}

                {job.show_expected_salary !== false && (
                  <div>
                    <label className="block text-[10px] font-mono uppercase tracking-widest text-gray-400 mb-1.5">Expected Salary (Monthly BDT)</label>
                    <input
                      type="number"
                      value={expectedSalary}
                      onChange={(e) => setExpectedSalary(e.target.value)}
                      placeholder="e.g. 18000"
                      className="w-full bg-salon-gray text-white text-xs border border-white/10 px-4 py-3 focus:outline-none focus:border-gold-400 transition-colors"
                    />
                  </div>
                )}

                {job.show_address !== false && (
                  <div>
                    <label className="block text-[10px] font-mono uppercase tracking-widest text-gray-400 mb-1.5">Present Address</label>
                    <input
                      type="text"
                      value={presentAddress}
                      onChange={(e) => setPresentAddress(e.target.value)}
                      placeholder="e.g. Banani, Dhaka"
                      className="w-full bg-salon-gray text-white text-xs border border-white/10 px-4 py-3 focus:outline-none focus:border-gold-400 transition-colors"
                    />
                  </div>
                )}
              </div>

              {job.show_cover_letter !== false && (
                <div>
                  <label className="block text-[10px] font-mono uppercase tracking-widest text-gray-400 mb-1.5">Cover Letter</label>
                  <textarea
                    rows={4}
                    value={coverLetter}
                    onChange={(e) => setCoverLetter(e.target.value)}
                    placeholder="Introduce yourself and outline your professional alignment..."
                    className="w-full bg-salon-gray text-white text-xs border border-white/10 p-4 focus:outline-none focus:border-gold-400 transition-colors"
                  />
                </div>
              )}

              {/* Dynamic Questions Rendering */}
              {job.questions.length > 0 && (
                <div className="space-y-4 border-t border-white/5 pt-6">
                  <h4 className="text-[10px] font-mono uppercase tracking-widest text-gold-400 mb-4">Additional Job Specific Questions</h4>

                  <div className="space-y-4">
                    {job.questions.map((q) => {
                      const ansKey = q.id;

                      return (
                        <div key={q.id} className="space-y-1.5">
                          <label className="block text-[10px] font-mono uppercase tracking-widest text-gray-300">
                            {q.question} {q.is_required ? '*' : ''}
                          </label>
                          {q.help_text && (
                            <span className="block text-[9px] text-gray-500 font-sans italic mb-1">{q.help_text}</span>
                          )}

                          {q.question_type === 'text' && (
                            <input
                              type="text"
                              required={q.is_required}
                              value={answers[ansKey] || ''}
                              onChange={(e) => handleCustomTextChange(ansKey, e.target.value)}
                              className="w-full bg-salon-gray text-white text-xs border border-white/10 px-4 py-3 focus:outline-none focus:border-gold-400"
                            />
                          )}

                          {q.question_type === 'textarea' && (
                            <textarea
                              rows={3}
                              required={q.is_required}
                              value={answers[ansKey] || ''}
                              onChange={(e) => handleCustomTextChange(ansKey, e.target.value)}
                              className="w-full bg-salon-gray text-white text-xs border border-white/10 p-4 focus:outline-none focus:border-gold-400"
                            />
                          )}

                          {q.question_type === 'number' && (
                            <input
                              type="number"
                              required={q.is_required}
                              value={answers[ansKey] || ''}
                              onChange={(e) => handleCustomTextChange(ansKey, e.target.value)}
                              className="w-full bg-salon-gray text-white text-xs border border-white/10 px-4 py-3 focus:outline-none focus:border-gold-400"
                            />
                          )}

                          {q.question_type === 'email' && (
                            <input
                              type="email"
                              required={q.is_required}
                              value={answers[ansKey] || ''}
                              onChange={(e) => handleCustomTextChange(ansKey, e.target.value)}
                              className="w-full bg-salon-gray text-white text-xs border border-white/10 px-4 py-3 focus:outline-none focus:border-gold-400"
                            />
                          )}

                          {q.question_type === 'phone' && (
                            <input
                              type="tel"
                              required={q.is_required}
                              value={answers[ansKey] || ''}
                              onChange={(e) => handleCustomTextChange(ansKey, e.target.value)}
                              className="w-full bg-salon-gray text-white text-xs border border-white/10 px-4 py-3 focus:outline-none focus:border-gold-400"
                            />
                          )}

                          {q.question_type === 'date' && (
                            <input
                              type="date"
                              required={q.is_required}
                              value={answers[ansKey] || ''}
                              onChange={(e) => handleCustomTextChange(ansKey, e.target.value)}
                              className="w-full bg-salon-gray text-white text-xs border border-white/10 px-4 py-3 focus:outline-none focus:border-gold-400"
                            />
                          )}

                          {q.question_type === 'yes_no' && (
                            <div className="flex gap-4 pt-1">
                              <label className="flex items-center gap-1.5 text-xs cursor-pointer">
                                <input
                                  type="radio"
                                  name={`q-${q.id}`}
                                  required={q.is_required}
                                  checked={answers[ansKey] === 'Yes'}
                                  onChange={() => handleCustomTextChange(ansKey, 'Yes')}
                                  className="h-4 w-4 text-[#32BBED]"
                                />
                                <span>Yes</span>
                              </label>
                              <label className="flex items-center gap-1.5 text-xs cursor-pointer">
                                <input
                                  type="radio"
                                  name={`q-${q.id}`}
                                  required={q.is_required}
                                  checked={answers[ansKey] === 'No'}
                                  onChange={() => handleCustomTextChange(ansKey, 'No')}
                                  className="h-4 w-4 text-[#32BBED]"
                                />
                                <span>No</span>
                              </label>
                            </div>
                          )}

                          {q.question_type === 'dropdown' && q.options && (
                            <select
                              required={q.is_required}
                              value={answers[ansKey] || ''}
                              onChange={(e) => handleCustomTextChange(ansKey, e.target.value)}
                              className="w-full bg-salon-gray border border-white/10 text-white text-xs px-3 py-3 focus:outline-none"
                            >
                              <option value="">Select Option</option>
                              {q.options.map((opt) => (
                                <option key={opt} value={opt}>{opt}</option>
                              ))}
                            </select>
                          )}

                          {q.question_type === 'radio' && q.options && (
                            <div className="flex flex-wrap gap-4 pt-1">
                              {q.options.map((opt) => (
                                <label key={opt} className="flex items-center gap-1.5 text-xs cursor-pointer">
                                  <input
                                    type="radio"
                                    name={`q-${q.id}`}
                                    required={q.is_required}
                                    checked={answers[ansKey] === opt}
                                    onChange={() => handleCustomTextChange(ansKey, opt)}
                                    className="h-4 w-4 text-[#32BBED]"
                                  />
                                  <span>{opt}</span>
                                </label>
                              ))}
                            </div>
                          )}

                          {q.question_type === 'checkbox' && q.options && (
                            <div className="flex flex-wrap gap-4 pt-1">
                              {q.options.map((opt) => {
                                const selected = answers[ansKey] || [];
                                return (
                                  <label key={opt} className="flex items-center gap-1.5 text-xs cursor-pointer">
                                    <input
                                      type="checkbox"
                                      checked={selected.includes(opt)}
                                      onChange={(e) => handleCustomCheckboxChange(ansKey, opt, e.target.checked)}
                                      className="h-4 w-4 text-[#32BBED]"
                                    />
                                    <span>{opt}</span>
                                  </label>
                                );
                              })}
                            </div>
                          )}

                          {q.question_type === 'multiselect' && q.options && (
                            <select
                              multiple
                              required={q.is_required}
                              value={answers[ansKey] || []}
                              onChange={(e) => {
                                const opts = Array.from(
                                  e.currentTarget.selectedOptions,
                                  (option: HTMLOptionElement) => option.value,
                                );
                                handleCustomTextChange(ansKey, opts);
                              }}
                              className="w-full bg-salon-gray border border-white/10 text-white text-xs px-3 py-3 focus:outline-none h-24"
                            >
                              {q.options.map((opt) => (
                                <option key={opt} value={opt}>{opt}</option>
                              ))}
                            </select>
                          )}

                          {q.question_type === 'file' && (
                            <input
                              type="file"
                              required={q.is_required}
                              onChange={(e) => handleCustomFileChange(q.id, e)}
                              className="w-full bg-salon-gray text-white text-xs border border-white/10 px-4 py-3 focus:outline-none"
                            />
                          )}
                        </div>
                      );
                    })}
                  </div>
                </div>
              )}

              {/* CV Upload */}
              <div className="border-t border-white/5 pt-6 space-y-2">
                <label className="block text-[10px] font-mono uppercase tracking-widest text-gray-400">CV / Resume Attachment *</label>
                <input
                  type="file"
                  required
                  accept=".pdf,.doc,.docx"
                  onChange={handleFileChange}
                  className="w-full bg-salon-gray text-white text-xs border border-white/10 px-4 py-3 focus:outline-none focus:border-gold-400"
                />
                <span className="block text-[8px] text-gray-500 uppercase tracking-wider">Approved Formats: PDF, DOC, DOCX • Maximum File Size: 5MB</span>
              </div>

              {/* Consent checkbox */}
              <div className="flex items-start gap-2 pt-2">
                <input
                  type="checkbox"
                  required
                  id="consent"
                  checked={consent}
                  onChange={(e) => setConsent(e.target.checked)}
                  className="h-4 w-4 mt-0.5 bg-black border-white/10 text-gold-400 focus:ring-0 cursor-pointer"
                />
                <label for="consent" className="text-[10px] text-gray-400 leading-normal cursor-pointer select-none">
                  I consent to sharing my professional details, career logs, and portfolios with the recruiting committee of Adonis Lounges for candidate mapping. *
                </label>
              </div>

              <button
                type="submit"
                disabled={submitting}
                className="w-full py-4 bg-[#32BBED] hover:bg-[#b08d3c] disabled:opacity-50 text-black font-serif text-xs font-bold uppercase tracking-widest transition-all cursor-pointer shadow-lg text-center"
              >
                {submitting ? "Transmitting Credentials..." : "Submit Application"}
              </button>
            </form>
          )}
        </div>
      </div>
    </div>
  );
};
