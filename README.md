PHP PDF-to-PNG Converter Using Docker, Ghostscript & AWS S3
A Dockerized PHP application that downloads PDF files from Amazon S3, converts each PDF page into a PNG image using Ghostscript, and uploads the generated images back to Amazon S3 using the AWS SDK for PHP.

The project uses Docker and Composer to provide a consistent, portable environment for PDF processing.

🚀 Features
Download PDF files from Amazon S3.
Convert multi-page PDFs into individual PNG images.
Use Ghostscript for PDF rendering.
Upload generated PNG files to Amazon S3.
Manage PHP dependencies using Composer.
Run the application in an isolated Docker container.
Configure AWS credentials and S3 paths through environment variables.
Automatically clean up temporary files.
Support configurable image resolution.
