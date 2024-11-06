import React, { useState } from 'react';
import Button from './Button';

const ContentKritik = ({ settings }) => {
    const [kritik, setKritik] = useState("");
    const [error, setError] = useState("");

    const handleSendKritik = () => {
        if (!kritik.trim()) {
            setError("Harap masukkan kritik dan saran Anda.");
            return;
        }

        // Jika ada teks, redirect ke URL
        setError(""); // Reset pesan error jika sudah ada teks
        window.location.href = `/send-kritik?kritik=${encodeURIComponent(kritik)}`;
    };

    return (
        <section className="w-full mx-auto bg-white py-12 px-4 sm:px-8 lg:px-12 text-orange-500 mt-12">
            <div className="bg-white p-8 rounded-lg shadow-lg">
                <h1 className="text-4xl sm:text-5xl font-bold mb-8 text-center">Kritik dan Saran</h1>
                <div className="flex flex-col items-center">
                    <textarea
                        value={kritik}
                        onChange={(e) => setKritik(e.target.value)}
                        maxLength="1000"
                        className="w-full max-w-lg p-4 border border-gray-300 rounded-lg resize-none focus:outline-none focus:ring-2 focus:ring-orange-500"
                        rows="6"
                        placeholder="Masukkan kritik dan saran Anda di sini..."
                    ></textarea>
                    {error && (
                        <p className="text-red-500 mt-2">{error}</p>
                    )}
                    <Button
                        text="Kirim Kritik & Saran"
                        onClick={handleSendKritik}
                        className="mt-8 bg-orange-500 text-white py-3 px-6 rounded-lg transition-transform transform hover:scale-105 duration-200 4xl:py-5 4xl:px-10"
                    />
                </div>
            </div>
        </section>
    );
};

export default ContentKritik;
