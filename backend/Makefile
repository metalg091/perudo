# compile command should look like: g++ -o main main.cpp uWebsockets/uSockets/*.o -lssl -lcrypto -lz
# vps arch: -march=neoverse-n1+crc+crypto+ssbs (maybe with clang++)
# Compiler
CC = g++
# Compiler flags
CFLAGS = -Wall
# Linker flags
LDFLAGS = -lssl -lcrypto -lz
# Source files
SRCS = main.cpp
# Object files
OBJS = $(SRCS:.cpp=.o)
# Output executable
TARGET = main.out

# Submodule directories
UWEBSOCKETS_DIR = uWebsockets
USOCKETS_DIR = $(UWEBSOCKETS_DIR)/uSockets

# Environment variable for submodules
WITH_OPENSSL = 1

all: check_submodules $(TARGET)

# Rule to check and update submodules
check_submodules:
	@echo "Checking submodules..."
	@if [ ! -d $(UWEBSOCKETS_DIR) ]; then \
		git submodule update --init --recursive; \
	fi
# Rule to link the executable
$(TARGET): $(OBJS) $(USOCKETS_DIR)/libusockets.a
	$(CC) $(CFLAGS) -o $(TARGET) $(OBJS) $(USOCKETS_DIR)/*.o $(LDFLAGS)

# Rule to compile source files into object files
%.o: %.cpp
	$(CC) $(CFLAGS) -c $< -o $@

# Rule to build uWebsockets and uSockets
$(USOCKETS_DIR)/libusockets.a: check_submodules
	WITH_OPENSSL=$(WITH_OPENSSL) $(MAKE) -C $(USOCKETS_DIR)

# Clean rule to remove generated files
clean: check_submodules
	rm -f $(OBJS) $(TARGET)
	$(MAKE) -C $(USOCKETS_DIR) clean
