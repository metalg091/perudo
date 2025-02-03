# compile command should look like: g++ -o main main.cpp uWebsockets/uSockets/*.o -lssl -lcrypto -lz
# vps arch: -march=neoverse-n1+crc+crypto+ssbs (maybe with clang++)
# Compiler flags lto is necessary (uSocket is compiled with lto)
CFLAGS = -Wall -O3 -flto -std=c++17
# Linker flags
LDFLAGS = -lssl -lcrypto -lz
# Source files
SRCS = src/main.cpp src/game.cpp
# Object files
OBJS = $(SRCS:.cpp=.o)
# Output executable
TARGET = build/bin/main.out

# Submodule directories
UWEBSOCKETS_DIR = src/uWebsockets
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

# Rule to create build directory
build:
	@mkdir -p build
	@mkdir -p build/bin


# Rule to link the executable
$(TARGET): $(OBJS) $(USOCKETS_DIR)/uSockets.a | build
	$(CXX) $(CFLAGS) -o $(TARGET) $(OBJS) $(USOCKETS_DIR)/uSockets.a $(LDFLAGS)

# Rule to compile source files into object files
build/%.o: src/%.cpp | build
	$(CXX) $(CFLAGS) -c $< -o $@

# Rule to build uWebsockets and uSockets
$(USOCKETS_DIR)/uSockets.a: check_submodules
	WITH_OPENSSL=$(WITH_OPENSSL) $(MAKE) -C $(USOCKETS_DIR)

# Clean rule to remove generated files
clean: check_submodules
	rm -f $(OBJS) $(TARGET)
	$(MAKE) -C $(USOCKETS_DIR) clean
