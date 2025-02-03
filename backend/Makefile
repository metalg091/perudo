# vps arch: -march=neoverse-n1+crc+crypto+ssbs (maybe with clang++)
# compile hello world for arm: clang++ --target=aarch64-linux-gnu --sysroot=/usr/aarch64-linux-gnu -mcpu=neoverse-n1 hello.cpp

# Set CC based on CXX
ifeq ($(CXX),g++)
    CC = gcc
else ifeq ($(CXX),clang++)
    CC = clang
endif

# Compiler flags lto is necessary (uSocket is compiled with lto)
CFLAGS += -Wall -O3 -flto -std=c++17
# Linker flags
LDFLAGS = -lssl -lcrypto -lz
# Source files
SRCS = $(wildcard src/*.cpp)
# Object files
OBJS = $(patsubst src/%.cpp, build/obj/%.o, $(SRCS))
# Output executable
TARGET = build/bin/perudo_backend

# Submodule directories
UWEBSOCKETS_DIR = src/uWebSockets
USOCKETS_DIR = $(UWEBSOCKETS_DIR)/uSockets
USOCKETS_LIB = $(USOCKETS_DIR)/uSockets.a

# Environment variable for submodules
WITH_OPENSSL ?= 1

# File to store the compiler name
COMPILER_FILE = build/compiler

all: check_compiler $(TARGET)

.PHONY: oracle check_compiler

oracle:
	@echo "This doesn't work"
	@Cancel
	$(MAKE) clean
	$(eval CXX := clang++)
	$(eval CC := clang)
	$(eval ARCH = --target=aarch64-linux-gnu --sysroot=/usr/aarch64-linux-gnu -mcpu=neoverse-n1)
	@echo "Compiling with clang++ for ARM"
	CXX=$(CXX) CC=$(CC) CFLAGS="$(ARCH)" $(MAKE) all


# Rule to check if the compiler has changed
check_compiler:
	@echo $(CXX)
	@echo $(CC)
	@if [ -f $(COMPILER_FILE) ]; then \
		if [ "$$(cat $(COMPILER_FILE))" != "$(CXX)" ]; then \
			echo "Compiler has changed, cleaning build..."; \
			$(MAKE) clean; \
		fi \
	fi

# Rule to check and update submodules
$(UWEBSOCKETS_DIR):
	@echo "Checking submodules..."
	@if [ ! -d $(UWEBSOCKETS_DIR) ]; then \
	    echo "Submodules not found, initializing..."; \
		git submodule update --init --recursive; \
	fi

# Rule to create build directory
build/obj:
	@mkdir -p build/obj
build/bin:
	@mkdir -p build/bin

# Rule to link the executable
$(TARGET): $(OBJS) $(USOCKETS_LIB)| build/bin
	$(CXX) $(CFLAGS) -o $(TARGET) $(OBJS) $(USOCKETS_LIB) $(LDFLAGS)
	@echo "$(CXX)" > $(COMPILER_FILE)

# Rule to compile source files into object files
build/obj/%.o: src/%.cpp | build/obj
	$(CXX) $(CFLAGS) -c $< -o $@

# Rule to build uSockets (passing compiler preference)
$(USOCKETS_LIB): $(UWEBSOCKETS_DIR)
	WITH_OPENSSL=$(WITH_OPENSSL) CC=$(CC) CXX=$(CXX) $(MAKE) -C $(USOCKETS_DIR)

# Clean rule to remove generated files
clean: $(UWEBSOCKETS_DIR)
	rm -f $(OBJS) $(TARGET)
	$(MAKE) -C $(USOCKETS_DIR) clean
	rm -f $(COMPILER_FILE)
