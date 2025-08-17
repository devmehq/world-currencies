from setuptools import setup, find_packages
import os

with open("README.md", "r", encoding="utf-8") as fh:
    long_description = fh.read()

setup(
    name="world-currencies",
    version="1.0.0",
    author="Your Name",
    author_email="your.email@example.com",
    description="A comprehensive library providing currency information for all world currencies",
    long_description=long_description,
    long_description_content_type="text/markdown",
    url="https://github.com/yourusername/world-currencies",
    packages=find_packages(where="src/python"),
    package_dir={"": "src/python"},
    package_data={
        "world_currencies": ["../../data/world-currencies.json"],
    },
    classifiers=[
        "Development Status :: 5 - Production/Stable",
        "Intended Audience :: Developers",
        "License :: OSI Approved :: MIT License",
        "Programming Language :: Python :: 3",
        "Programming Language :: Python :: 3.7",
        "Programming Language :: Python :: 3.8",
        "Programming Language :: Python :: 3.9",
        "Programming Language :: Python :: 3.10",
        "Programming Language :: Python :: 3.11",
        "Programming Language :: Python :: 3.12",
        "Topic :: Software Development :: Libraries :: Python Modules",
        "Topic :: Office/Business :: Financial",
    ],
    python_requires=">=3.7",
    install_requires=[],
    extras_require={
        "dev": [
            "pytest>=7.0",
            "black>=23.0",
            "isort>=5.0",
            "mypy>=1.0",
            "flake8>=6.0",
        ]
    },
)